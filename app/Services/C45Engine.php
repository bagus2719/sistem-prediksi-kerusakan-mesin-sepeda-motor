<?php

namespace App\Services;

use App\Models\Training;
use App\Models\C45Model;
use Illuminate\Support\Facades\Log;

class C45Engine
{
    /**
     * Build the C4.5 Decision Tree Model based on available Training data.
     * Returns the created C45Model object or null if failed.
     */
    public function generateModel()
    {
        // 1. Fetch all training datasets with their Kerusakan result and Motor's sistem_pembakaran
        $trainings = Training::with('motor')->get();

        if ($trainings->isEmpty()) {
            throw new \Exception('Data training kosong. Silakan import dataset terlebih dahulu.');
        }

        // 2. Format data into an array format suitable for the engine
        $dataset = [];
        $attributes = [];

        foreach ($trainings as $training) {
            $row = [];
            
            // Atribut: Sistem Pembakaran (Injeksi / Karburator)
            // if you want to include it. As per previous open questions, we can include it.
            if ($training->motor) {
                $row['sistem_pembakaran'] = $training->motor->sistem_pembakaran;
            } else {
                $row['sistem_pembakaran'] = 'Umum';
            }

            // Atribut: Gejala (G01 - G14)
            $gejalas = is_string($training->data_gejala) ? json_decode($training->data_gejala, true) : $training->data_gejala;
            if (is_array($gejalas)) {
                foreach ($gejalas as $kode => $nilai) {
                    $row[$kode] = $nilai; // e.g. "G01" => 1
                }
            }

            // Target/Class: Kerusakan (karena C4.5 akan mengklasifikasikan ini)
            $row['class'] = $training->kerusakan_id;

            $dataset[] = $row;
        }

        if (empty($dataset)) {
            throw new \Exception('Data training tidak memiliki format matriks fitur yang valid.');
        }

        // 3. Collect unique attributes to evaluate (all keys except 'class')
        $attributes = array_keys($dataset[0]);
        $attributes = array_values(array_filter($attributes, fn($key) => $key !== 'class'));

        // 4. Start recursive tree building
        $treeData = $this->buildTree($dataset, $attributes);

        // 5. Store the model in Database
        // Nonaktifkan semua model lama
        C45Model::where('is_active', true)->update(['is_active' => false]);

        // Simpan model baru
        $model = C45Model::create([
            'tree_data' => $treeData,
            'accuracy' => 100, // Optional: calculating accuracy using test split can be done later
            'is_active' => true
        ]);

        return $model;
    }

    /**
     * Recursive function to build the Decision Tree.
     * 
     * @param array $dataset Current subset of data
     * @param array $attributes Available attributes to split on
     * @return array Node structure
     */
    private function buildTree($dataset, $attributes)
    {
        // Check if dataset is empty
        if (empty($dataset)) {
            return ['type' => 'leaf', 'class' => null]; // Cannot determine
        }

        // Base case 1: If all instances in dataset have the SAME class, return leaf node
        $classes = array_column($dataset, 'class');
        $uniqueClasses = array_unique($classes);
        if (count($uniqueClasses) === 1) {
            $c = array_values($uniqueClasses)[0];
            return ['type' => 'leaf', 'class' => $c, 'probabilities' => [$c => 100.0]];
        }

        // Base case 2: If no attributes left to split, return leaf node with MAJORITY class
        if (empty($attributes)) {
            $majorityClass = $this->getMajorityClass($classes);
            return ['type' => 'leaf', 'class' => $majorityClass, 'probabilities' => $this->calculateDistribution($classes)];
        }

        // Calculate Entropy of current dataset (S)
        $entropyS = $this->calculateEntropy($dataset);

        // Calculate Gain Ratio for each attribute
        $bestGainRatio = -1;
        $bestAttribute = null;

        foreach ($attributes as $attribute) {
            $gainRatio = $this->calculateGainRatio($dataset, $attribute, $entropyS);
            if ($gainRatio > $bestGainRatio) {
                $bestGainRatio = $gainRatio;
                $bestAttribute = $attribute;
            }
        }

        // Base case 3: If best gain ratio is 0 or negative, we can't split usefully anymore, return majority class
        if ($bestGainRatio <= 0) {
            $majorityClass = $this->getMajorityClass($classes);
            return ['type' => 'leaf', 'class' => $majorityClass, 'probabilities' => $this->calculateDistribution($classes)];
        }

        // Create the internal node
        $node = [
            'type' => 'node',
            'attribute' => $bestAttribute,
            'children' => []
        ];

        // Get unique values of the best attribute in the current dataset
        $attributeValues = array_unique(array_column($dataset, $bestAttribute));

        // Remove the chosen attribute from the list for the next recursive steps
        $remainingAttributes = array_values(array_filter($attributes, fn($attr) => $attr !== $bestAttribute));

        // Recursively build branches for each unique value
        foreach ($attributeValues as $value) {
            // Subset S_v (data where attribute = value)
            $subset = array_filter($dataset, fn($row) => isset($row[$bestAttribute]) && $row[$bestAttribute] === $value);
            
            if (empty($subset)) {
                // If subset is empty, attach a leaf with majority class of the PARENT dataset
                $majorityClass = $this->getMajorityClass($classes);
                $node['children'][$value] = ['type' => 'leaf', 'class' => $majorityClass, 'probabilities' => $this->calculateDistribution($classes)];
            } else {
                // Recursion
                $node['children'][$value] = $this->buildTree(array_values($subset), $remainingAttributes);
            }
        }

        return $node;
    }

    /**
     * Calculate Shannon Entropy of a dataset.
     */
    private function calculateEntropy($dataset)
    {
        $totalItems = count($dataset);
        if ($totalItems === 0) return 0;

        $classCounts = [];
        foreach ($dataset as $row) {
            $c = $row['class'];
            if (!isset($classCounts[$c])) {
                $classCounts[$c] = 0;
            }
            $classCounts[$c]++;
        }

        $entropy = 0;
        foreach ($classCounts as $count) {
            $probability = $count / $totalItems;
            $entropy -= $probability * log($probability, 2);
        }

        return $entropy;
    }

    /**
     * Calculate Gain Ratio for a specific attribute.
     * Gain Ratio = Information Gain / Split Info
     */
    private function calculateGainRatio($dataset, $attribute, $entropyS)
    {


        
        $totalItems = count($dataset);
        if ($totalItems === 0) return 0;

        // Group dataset by the attribute's values
        $subsets = [];
        foreach ($dataset as $row) {
            $val = isset($row[$attribute]) ? $row[$attribute] : null;
            if (!isset($subsets[$val])) {
                $subsets[$val] = [];
            }
            $subsets[$val][] = $row;
        }

        // Sum up the weighted entropy (for Info Gain) and Split Info
        $subsetEntropySum = 0;
        $splitInfo = 0;

        foreach ($subsets as $val => $subset) {
            $subsetSize = count($subset);
            $weight = $subsetSize / $totalItems;
            
            // For Information Gain
            $subsetEntropy = $this->calculateEntropy($subset);
            $subsetEntropySum += $weight * $subsetEntropy;

            // For Split Info
            $splitInfo -= $weight * log($weight, 2);
        }

        // Information Gain = Entropy(S) - Sum(Weight * Entropy(S_v))
        $infoGain = $entropyS - $subsetEntropySum;

        // If SplitInfo is 0 (all instances have the same attribute value), Gain Ratio is undefined/0
        if ($splitInfo == 0) {
            return 0;
        }

        // Gain Ratio = Info Gain / Split Info
        return $infoGain / $splitInfo;
    }

    /**
     * Helper to get the most frequent class in a given array of classes.
     */
    private function getMajorityClass($classes)
    {
        if (empty($classes)) return null;
        
        $counts = array_count_values($classes);
        arsort($counts);
        return array_key_first($counts);
    }

    /**
     * Calculate distribution (confidence percentages) of classes.
     */
    private function calculateDistribution($classes)
    {
        if (empty($classes)) return [];
        $total = count($classes);
        $counts = array_count_values($classes);
        $distribution = [];
        foreach ($counts as $class => $count) {
            $distribution[$class] = round(($count / $total) * 100, 2);
        }
        arsort($distribution);
        return $distribution;
    }
}
