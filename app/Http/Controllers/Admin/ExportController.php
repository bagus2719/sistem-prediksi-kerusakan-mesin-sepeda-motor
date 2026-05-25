<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Training;
use App\Models\Gejala;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Font;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExportController extends Controller
{
    public function exportPengujian()
    {
        $trainings = Training::with(['motor', 'kerusakan'])->get();
        $gejalas   = Gejala::all()->keyBy('kode');

        // ── Kumpulkan kasus unik ───────────────────────────────────────────────
        $uniqueCases = [];

        foreach ($trainings as $t) {
            $pembakaran = $t->motor ? $t->motor->sistem_pembakaran : 'Umum';
            $dataGejala = is_string($t->data_gejala)
                ? json_decode($t->data_gejala, true)
                : $t->data_gejala;

            $symptomNames = [];
            if (is_array($dataGejala)) {
                ksort($dataGejala);
                foreach ($dataGejala as $kode => $val) {
                    if ($val == 1 && isset($gejalas[$kode])) {
                        $symptomNames[] = '• ' . $kode . ' - ' . $gejalas[$kode]->nama_gejala;
                    }
                }
            }

            $symptomStr   = implode("\n", $symptomNames);
            $kerusakanName = $t->kerusakan
                ? $t->kerusakan->kode . ' - ' . $t->kerusakan->nama_kerusakan
                : 'Unknown';

            $hash = md5($pembakaran . $symptomStr . $kerusakanName);

            if (!isset($uniqueCases[$hash])) {
                $uniqueCases[$hash] = [
                    'sistem_pembakaran' => $pembakaran,
                    'gejala'            => $symptomStr,
                    'prediksi'          => $kerusakanName,
                ];
            }
        }

        // ── Buat Spreadsheet ──────────────────────────────────────────────────
        $spreadsheet = new Spreadsheet();
        $sheet       = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Pengujian Pakar');

        // Lebar kolom
        $sheet->getColumnDimension('A')->setWidth(6);   // No
        $sheet->getColumnDimension('B')->setWidth(22);  // Sistem Pembakaran
        $sheet->getColumnDimension('C')->setWidth(55);  // Gejala
        $sheet->getColumnDimension('D')->setWidth(30);  // Prediksi
        $sheet->getColumnDimension('E')->setWidth(10);  // Benar
        $sheet->getColumnDimension('F')->setWidth(10);  // Salah
        $sheet->getColumnDimension('G')->setWidth(38);  // Keterangan

        // ── Baris 1: Judul ────────────────────────────────────────────────────
        $sheet->mergeCells('A1:G1');
        $sheet->setCellValue('A1', 'FORMAT PENGUJIAN PAKAR – SISTEM PREDIKSI C4.5');
        $sheet->getStyle('A1')->applyFromArray([
            'font'      => ['bold' => true, 'size' => 14, 'name' => 'Arial'],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical'   => Alignment::VERTICAL_CENTER,
            ],
            'fill' => [
                'fillType'   => Fill::FILL_SOLID,
                'startColor' => ['argb' => 'FF1F3864'],
            ],
        ]);
        $sheet->getStyle('A1')->getFont()->getColor()->setARGB('FFFFFFFF');
        $sheet->getRowDimension(1)->setRowHeight(30);

        // ── Baris 2: Kosong (spacer) ──────────────────────────────────────────
        $sheet->getRowDimension(2)->setRowHeight(6);

        // ── Baris 3: Header Kolom ─────────────────────────────────────────────
        $headers = [
            'A3' => 'No',
            'B3' => 'Sistem Pembakaran',
            'C3' => 'Gejala yang Dialami',
            'D3' => 'Prediksi Sistem (Metode C4.5)',
            'E3' => 'Benar',
            'F3' => 'Salah',
            'G3' => 'Keterangan / Catatan Pakar',
        ];

        $headerStyle = [
            'font' => [
                'bold' => true,
                'size' => 11,
                'name' => 'Arial',
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical'   => Alignment::VERTICAL_CENTER,
                'wrapText'   => true,
            ],
            'fill' => [
                'fillType'   => Fill::FILL_SOLID,
                'startColor' => ['argb' => 'FF2E75B6'],
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color'       => ['argb' => 'FFFFFFFF'],
                ],
            ],
        ];

        foreach ($headers as $cell => $label) {
            $sheet->setCellValue($cell, $label);
        }
        $sheet->getStyle('A3:G3')->applyFromArray($headerStyle);
        $sheet->getStyle('A3:G3')->getFont()->getColor()->setARGB('FFFFFFFF');
        $sheet->getRowDimension(3)->setRowHeight(36);

        // ── Baris Data (mulai baris 4) ────────────────────────────────────────
        $row       = 4;
        $no        = 1;
        $fillEven  = 'FFDCE6F1'; // biru muda
        $fillOdd   = 'FFFFFFFF'; // putih

        foreach ($uniqueCases as $case) {
            $isEven    = ($no % 2 === 0);
            $bgColor   = $isEven ? $fillEven : $fillOdd;

            // Hitung tinggi baris berdasarkan jumlah gejala
            $gejalaLines = array_filter(explode("\n", $case['gejala']));
            $lineCount   = max(count($gejalaLines), 1);
            $rowHeight   = max($lineCount * 18, 24);

            $sheet->getRowDimension($row)->setRowHeight($rowHeight);

            // Isi sel
            $sheet->setCellValue('A' . $row, $no);
            $sheet->setCellValue('B' . $row, $case['sistem_pembakaran']);
            $sheet->setCellValue('C' . $row, $case['gejala']);
            $sheet->setCellValue('D' . $row, $case['prediksi']);
            // E, F, G dikosongkan (diisi pakar)

            // Style per baris
            $dataStyle = [
                'font' => ['size' => 10, 'name' => 'Arial'],
                'fill' => [
                    'fillType'   => Fill::FILL_SOLID,
                    'startColor' => ['argb' => $bgColor],
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color'       => ['argb' => 'FFBFBFBF'],
                    ],
                ],
            ];

            $sheet->getStyle("A{$row}:G{$row}")->applyFromArray($dataStyle);

            // Alignment per kolom
            $sheet->getStyle("A{$row}")->getAlignment()
                ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                ->setVertical(Alignment::VERTICAL_TOP);

            $sheet->getStyle("B{$row}")->getAlignment()
                ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                ->setVertical(Alignment::VERTICAL_TOP);

            $sheet->getStyle("C{$row}")->getAlignment()
                ->setHorizontal(Alignment::HORIZONTAL_LEFT)
                ->setVertical(Alignment::VERTICAL_TOP)
                ->setWrapText(true);

            $sheet->getStyle("D{$row}")->getAlignment()
                ->setHorizontal(Alignment::HORIZONTAL_LEFT)
                ->setVertical(Alignment::VERTICAL_TOP)
                ->setWrapText(true);

            $sheet->getStyle("E{$row}:G{$row}")->getAlignment()
                ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                ->setVertical(Alignment::VERTICAL_TOP);

            $row++;
            $no++;
        }

        // ── Freeze pane di header ─────────────────────────────────────────────
        $sheet->freezePane('A4');

        // ── Auto-filter pada header ───────────────────────────────────────────
        $sheet->setAutoFilter('A3:G3');

        // ── Border luar tebal untuk seluruh tabel ─────────────────────────────
        $lastRow = $row - 1;
        if ($lastRow >= 3) {
            $sheet->getStyle("A3:G{$lastRow}")->getBorders()
                ->getOutline()
                ->setBorderStyle(Border::BORDER_MEDIUM)
                ->getColor()->setARGB('FF1F3864');
        }

        // ── Print setup ───────────────────────────────────────────────────────
        $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
        $sheet->getPageSetup()->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);
        $sheet->getPageSetup()->setFitToWidth(1);
        $sheet->getPageSetup()->setFitToHeight(0);
        $sheet->getHeaderFooter()
            ->setOddHeader('&C&B Format Pengujian Pakar – Sistem Prediksi C4.5');
        $sheet->getHeaderFooter()
            ->setOddFooter('&LDicetak: &D &T&RHalaman &P dari &N');

        // ── Stream response ke browser ─────────────────────────────────────────
        $filename = 'Skenario_Pengujian_Pakar_' . date('Ymd') . '.xlsx';

        $response = new StreamedResponse(function () use ($spreadsheet) {
            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
        });

        $response->headers->set('Content-Type',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->headers->set('Content-Disposition',
            'attachment; filename="' . $filename . '"');
        $response->headers->set('Cache-Control',
            'max-age=0, must-revalidate, no-cache, no-store');

        return $response;
    }
}