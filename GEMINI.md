# MotorCare C4.5 System Context

## Project Overview
MotorCare is an expert system for predicting motorcycle engine damage using the **C4.5 Decision Tree Algorithm**. It is built using **Laravel 11**, **Livewire v3**, and **Tailwind CSS**.

## Key Architectural Decisions

### 1. Algorithm Engine (C4.5)
- The core algorithm is located at `app/Services/C45Engine.php`.
- **Forced Root Node**: The C4.5 tree is forced to split by `sistem_pembakaran` (Injeksi vs Karburator) at the very root to logically separate the datasets immediately.
- **Pre-pruning**: The tree uses a `min_instances` threshold (currently 2) to prevent overfitting.
- **Pure Confidence**: The prediction confidence is derived **strictly** from the probability distribution of the leaf node. We do NOT manipulate the primary C4.5 confidence score with similarity algorithms.

### 2. Fallback Mechanism
- If the C4.5 tree returns fewer than 3 predictions (due to high node purity), the system uses a **Jaccard Similarity** algorithm to find alternative predictions and fill the "Top 3" list.
- Alternative predictions are artificially capped so their confidence never exceeds the primary C4.5 prediction.

### 3. Evaluation & Testing
- The system includes a Confusion Matrix evaluation tool located in `app/Livewire/Admin/Pengujian/Index.php`.
- The evaluation calculates Accuracy, Macro Precision, and Macro Recall.
- The system currently uses **100% of the dataset for training** (no train-test split), as the dataset represents definitive expert rules rather than random samples. The evaluation is done against the training set.

## Coding Guidelines
1. **Livewire 3**: Follow Livewire v3 conventions (use `wire:navigate` for SPA-like transitions, `#[Validate]` attributes where appropriate).
2. **Tailwind CSS**: Use utility classes for styling. Avoid writing custom CSS unless absolutely necessary. Maintain the current modern, clean, and professional UI aesthetic (using Indigo, Emerald, and Rose accents).
3. **Language**: The application interface and code comments are primarily written in **Indonesian**. Please continue using Indonesian for user-facing text and code comments to maintain consistency.
4. **Tooling**: Always verify database migrations before making schema changes. Use `artisan` commands for creating new components.
