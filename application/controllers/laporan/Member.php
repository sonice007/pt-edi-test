<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Member extends Render_Controller
{

    public function index()
    {
        // Page Settings
        $this->title = $this->getMenuTitle();
        $this->navigation = ['Laporan', 'Data Member '];
        $this->plugins = [];

        // Breadcrumb setting
        $this->breadcrumb_1 = 'Home';
        $this->breadcrumb_1_url = '#';
        $this->breadcrumb_2 = 'Laporan';
        $this->breadcrumb_2_url = "#";
        $this->breadcrumb_2 = 'Data Member';
        $this->breadcrumb_2_url = base_url().'laporan/member';

        $this->content = 'laporan/member';
        // Send data to view
        $this->render();
    }

    public function flat()
    {

        $details = $this->model->getFlat();

        $bulan_array = [
            1 => 'Januari',
            2 => 'February',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember',
        ];
        $today_m = (int)Date("m");
        $today_d = (int)Date("d");
        $today_y = (int)Date("Y");

        $last_date_of_this_month =  date('t', strtotime(date("Y-m-d")));

        $date = $today_d . " " . $bulan_array[$today_m] . " " . $today_y;

        // laporan baru
        $row = 1;
        $col_start = "A";
        $col_end = "M";
        $title_excel = "CmMS Member - Flat";
        // Header excel ================================================================================================
        $spreadsheet = new PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Dokumen Properti
        $spreadsheet->getProperties()
            ->setCreator("Audit System End to End")
            ->setLastModifiedBy("Administrator")
            ->setTitle($title_excel)
            ->setSubject("Administrator")
            ->setDescription("List Profile $date")
            ->setKeywords("Laporan, Report")
            ->setCategory("Laporan, Report");
        // set default font
        $spreadsheet->getDefaultStyle()->getFont()->setName('Calibri');
        $spreadsheet->getDefaultStyle()->getFont()->setSize(11);


        // header 2 ====================================================================================================
        $row += 1;
        $sheet->mergeCells($col_start . $row . ":" . $col_end . $row)
            ->setCellValue("A$row", "" . $this->getMenuTitle());
        $sheet->getStyle($col_start . $row . ":" . $col_end . $row)->applyFromArray([
            "font" => [
                "bold" => true,
                "size" => 13
            ],
            "alignment" => [
                "horizontal" => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
        ]);

        // Tabel =======================================================================================================
        // Tabel Header
        $row += 2;
        $styleArray = [
            'font' => [
                'bold' => true,
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => [
                    'rgb' => '93C5FD',
                ]
            ],
        ];
        $sheet->getStyle($col_start . $row . ":" . $col_end . $row)->applyFromArray($styleArray);
        $row++;
        $styleArray['fill']['startColor']['rgb'] = 'E5E7EB';
        $sheet->getStyle($col_start . $row . ":" . $col_end . $row)->applyFromArray($styleArray);

        // poin-poin header disini
        $headers = [
            'No',
            'Kode',
            'Nama Lengkap',
            'Jenis Kelamin',
            'Photo',
            'Flag',
            'Tanggal Registrasi',
            'Tanggal Lahir',
            'Tanggal Meninggal',
            'Keanggotaan',
            'Kategorial',
            'Wilayah',
            'Tanggal',
        ];

        // apply header
        for ($i = 0; $i < count($headers); $i++) {
            $sheet->setCellValue(chr(65 + $i) . ($row - 1), $headers[$i]);
            $sheet->setCellValue(chr(65 + $i) . $row, ($i + 1));
        }

        // tabel body
        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '000000'],
                ],
            ],
            "alignment" => [
                'wrapText' => TRUE,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP
            ]
        ];
        $start_tabel = $row + 1;
        foreach ($details as $detail) {
            $c = 0;
            $tanggal = is_null($detail['updated_at']) || $detail['updated_at'] == '' ?  $detail['created_at'] :  $detail['updated_at'];
            $tanggal = date("d-m-Y", strtotime($tanggal));
            if($detail['kode'] != null){
                $row++;
                if($detail['keanggotaan_nama'] != ""){
                    $keanggotaan = $detail['keanggotaan_nama']. " (".$detail['keanggotaan_inisial'].")";
                }else{
                    $keanggotaan = "";
                }
                $sheet->setCellValue(chr(65 + $c) . "$row", ($row - 5));
                $sheet->setCellValue(chr(65 + ++$c) . "$row", $detail['kode']);
                $sheet->setCellValue(chr(65 + ++$c) . "$row", $detail['nama_belakang']." ".$detail['nama_depan']);
                $sheet->setCellValue(chr(65 + ++$c) . "$row", $detail['jenis_kelamin']);
                $sheet->setCellValue(chr(65 + ++$c) . "$row", $detail['photo']);
                $sheet->setCellValue(chr(65 + ++$c) . "$row", $detail['flag']);
                $sheet->setCellValue(chr(65 + ++$c) . "$row", $detail['tanggal_registrasi']);
                $sheet->setCellValue(chr(65 + ++$c) . "$row", $detail['tanggal_lahir']);
                $sheet->setCellValue(chr(65 + ++$c) . "$row", $detail['tanggal_meninggal']);
                $sheet->setCellValue(chr(65 + ++$c) . "$row", $keanggotaan);
                $sheet->setCellValue(chr(65 + ++$c) . "$row", $detail['kategorial']);
                $sheet->setCellValue(chr(65 + ++$c) . "$row", $detail['wilayah']);
                $sheet->setCellValue(chr(65 + ++$c) . "$row",  $tanggal);
            }
        }
        // format
        // nomor center
        $sheet->getStyle($col_start . $start_tabel . ":" . $col_start . $row)
            ->getAlignment()
            ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        // border all data
        $sheet->getStyle($col_start . $start_tabel . ":" . $col_end . $row)
            ->applyFromArray($styleArray);

        // $code_rm = '_-[$RM-ms-MY]* #.##0,00_-;-[$RM-ms-MY]* #.##0,00_-;_-[$RM-ms-MY]* "-"??_-;_-@_-';
        // $sheet->getStyle("F" . $start_tabel . ":" . $col_end . $row)->getNumberFormat()->setFormatCode($code_rm);

        // $sheet->getStyle("F" . $start_tabel . ":" . $col_end . $row)->getNumberFormat()->setFormatCode($code_rm);

        // set alignment
        $sheet->getStyle("A" . $start_tabel . ":" . "A" . $row)->getAlignment()->setHorizontal('center');
        // $sheet->getStyle("B" . $start_tabel . ":" . "B" . $row)->getAlignment()->setHorizontal('center');
        // $sheet->getStyle("C" . $start_tabel . ":" . "C" . $row)->getAlignment()->setHorizontal('center');
        // $sheet->getStyle("C" . $start_tabel . ":D" . $row)
        //     ->getAlignment()
        //     ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);

        // footer
        // $row += 3;
        // $sheet->setCellValue("Q" . $row, "Kasui, $date");

        // $row += 3;
        // $sheet->setCellValue("Q" . $row, "(.....................................)");

        // function for width column
        function w($width)
        {
            return 0.71 + $width;
        }


        // set width column
        $spreadsheet->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('M')->setAutoSize(true);
        // set  printing area
        $spreadsheet->getActiveSheet()->getPageSetup()->setPrintArea($col_start . '1:' . $col_end . $row);
        $spreadsheet->getActiveSheet()->getPageSetup()
            ->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_PORTRAIT);
        $spreadsheet->getActiveSheet()->getPageSetup()
            ->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);

        // margin
        $spreadsheet->getActiveSheet()->getPageMargins()->setTop(1);
        $spreadsheet->getActiveSheet()->getPageMargins()->setRight(0);
        $spreadsheet->getActiveSheet()->getPageMargins()->setLeft(0);
        $spreadsheet->getActiveSheet()->getPageMargins()->setBottom(0);

        // page center on
        $spreadsheet->getActiveSheet()->getPageSetup()->setHorizontalCentered(true);
        $spreadsheet->getActiveSheet()->getPageSetup()->setVerticalCentered(false);

        // $writer = new PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        // $writer->save($title_excel);
        // header("Location: " . base_url($title_excel));
        $writer = new PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);


        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $title_excel . '.xlsx"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
    }
    
    public function AllMember()
    {

        $details = $this->model->getAllMember();
        
        $bulan_array = [
            1 => 'Januari',
            2 => 'February',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember',
        ];
        $today_m = (int)Date("m");
        $today_d = (int)Date("d");
        $today_y = (int)Date("Y");

        $last_date_of_this_month =  date('t', strtotime(date("Y-m-d")));

        $date = $today_d . " " . $bulan_array[$today_m] . " " . $today_y;

        // laporan baru
        $row = 1;
        $col_start = "A";
        $col_end = "S";
        $title_excel = "CmMS Data Member";
        // Header excel ================================================================================================
        $spreadsheet = new PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Dokumen Properti
        $spreadsheet->getProperties()
            ->setCreator("Audit System End to End")
            ->setLastModifiedBy("Administrator")
            ->setTitle($title_excel)
            ->setSubject("Administrator")
            ->setDescription("List Profile $date")
            ->setKeywords("Laporan, Report")
            ->setCategory("Laporan, Report");
        // set default font
        $spreadsheet->getDefaultStyle()->getFont()->setName('Calibri');
        $spreadsheet->getDefaultStyle()->getFont()->setSize(11);


        // header 2 ====================================================================================================
        // $row += 1;
        // $sheet->mergeCells($col_start . $row . ":" . $col_end . $row)
        //     ->setCellValue("A$row", "" . $this->getMenuTitle());
        // $sheet->getStyle($col_start . $row . ":" . $col_end . $row)->applyFromArray([
        //     "font" => [
        //         "bold" => true,
        //         "size" => 13
        //     ],
        //     "alignment" => [
        //         "horizontal" => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
        //     ],
        // ]);

        // Tabel =======================================================================================================
        // Tabel Header
        // $row += 2;
        $styleArray = [
            'font' => [
                'bold' => true,
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => [
                    'rgb' => '93C5FD',
                ]
            ],
        ];
        $sheet->getStyle($col_start . $row . ":" . $col_end . $row)->applyFromArray($styleArray);
        $row++;
        $styleArray['fill']['startColor']['rgb'] = 'E5E7EB';
        // $sheet->getStyle($col_start . $row . ":" . $col_end . $row)->applyFromArray($styleArray);

        // poin-poin header disini
        $headers = [
            'Kode',
            'Status Aktif',
            'Nama Lengkap',
            'Tanggal Registrasi',
            'Tanggal Lahir',
            'Tanggal Meninggal',
            'Jenis Kelamin',
            'Posisi Dalam Keluarga',
            'Nama Keluarga',
            'Keanggotaan',
            'Alamat Lengkap',
            'Wilayah',
            'Kategorial',
            'Telepon',
            'Tel Mobile',
            'Email',
            'P Formal 1',
            'P Formal 2',
            'P Formal Nikah'
        ];

        // apply header
        for ($i = 0; $i < count($headers); $i++) {
            $sheet->setCellValue(chr(65 + $i) . ($row - 1), $headers[$i]);
            // $sheet->setCellValue(chr(65 + $i) . $row, ($i + 1));
        }

        // tabel body
        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '000000'],
                ],
            ],
            "alignment" => [
                'wrapText' => TRUE,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP
            ]
        ];
        $start_tabel = $row - 1;
        foreach ($details as $detail) {
            $c = -1;
            if($detail['kode'] != null){
                
                
                $get_tipe_kontak_1 = $this->db->select('keterangan')->get_where('contact', ['id_profile' => $detail['id'], 'id_tipe_contact' => 3, 'status' => 1])->row_array();
                $get_tipe_kontak_2 = $this->db->select('keterangan')->get_where('contact', ['id_profile' => $detail['id'], 'id_tipe_contact' => 2, 'status' => 1])->row_array();
                $get_tipe_kontak_3 = $this->db->select('keterangan')->get_where('contact', ['id_profile' => $detail['id'], 'id_tipe_contact' => 1, 'status' => 1])->row_array();
                if($get_tipe_kontak_1 != null){
                    $kontak1 = $get_tipe_kontak_1['keterangan'];
                }else{
                    $kontak1 = "";
                }
                
                if($get_tipe_kontak_2 != null){
                    $kontak2 = $get_tipe_kontak_2['keterangan'];
                }else{
                    $kontak2 = "";
                }
                
                if($get_tipe_kontak_3 != null){
                    $kontak3 = $get_tipe_kontak_3['keterangan'];
                }else{
                    $kontak3 = "";
                }
                
                $data_formal_1 = $this->db->select('tanggal_data_formal as tanggal')->get_where('data_formal', ['id_profile' => $detail['id'], 'dtm_peristiwa_id' => 2, 'status' => 1])->row_array();
                $data_formal_2 = $this->db->select('tanggal_data_formal as tanggal')->get_where('data_formal', ['id_profile' => $detail['id'], 'dtm_peristiwa_id' => 3, 'status' => 1])->row_array();
                $data_formal_3 = $this->db->select('tanggal_data_formal as tanggal')->get_where('data_formal', ['id_profile' => $detail['id'], 'dtm_peristiwa_id' => 7, 'status' => 1])->row_array();
                if($data_formal_1 != null){
                    $dataformal1 = $data_formal_1['tanggal'];
                    $dataformal1 = date("d/m/Y", strtotime($dataformal1));
                }else{
                    $dataformal1 = "";
                }
                
                if($data_formal_2 != null){
                    $dataformal2 = $data_formal_2['tanggal'];
                    $dataformal2 = date("d/m/Y", strtotime($dataformal2));
                }else{
                    $dataformal2 = "";
                }
                
                if($data_formal_3 != null){
                    $dataformal3 = $data_formal_3['tanggal'];
                    $dataformal3 = date("d/m/Y", strtotime($dataformal3));
                }else{
                    $dataformal3 = "";
                }
                
                if($detail['tanggal_meninggal'] == '0000-00-00'){
                    $tanggal_meninggal = "";
                }else{
                    $tanggal_meninggal = date("d/m/Y", strtotime($detail['tanggal_meninggal']));
                }
                
                if($detail['tanggal_lahir'] == '0000-00-00'){
                    $tanggal_lahir = "";
                }else{
                    $tanggal_lahir = date("d/m/Y", strtotime($detail['tanggal_lahir']));
                }
                
                if($detail['tanggal_lahir'] == '0000-00-00'){
                    $tanggal_lahir = "";
                }else{
                    $tanggal_lahir = date("d/m/Y", strtotime($detail['tanggal_lahir']));
                }
                
                if($detail['tanggal_registrasi'] == '0000-00-00'){
                    $tanggal_registrasi = "";
                }else{
                    $tanggal_registrasi = date("d/m/Y", strtotime($detail['tanggal_registrasi']));
                }
                
                
                $sheet->setCellValue(chr(65 + ++$c) . "$row", $detail['kode']);
                $sheet->setCellValue(chr(65 + ++$c) . "$row", $detail['flag_str']);
                $sheet->setCellValue(chr(65 + ++$c) . "$row", $detail['nama_belakang']." ".$detail['nama_depan']);
                $sheet->setCellValue(chr(65 + ++$c) . "$row", $tanggal_registrasi);
                $sheet->setCellValue(chr(65 + ++$c) . "$row", $tanggal_lahir);
                $sheet->setCellValue(chr(65 + ++$c) . "$row", $tanggal_meninggal);
                $sheet->setCellValue(chr(65 + ++$c) . "$row", $detail['jenis_kelamin_str']);
                $sheet->setCellValue(chr(65 + ++$c) . "$row", $detail['posisi']);
                $sheet->setCellValue(chr(65 + ++$c) . "$row", $detail['keluarga']);
                $sheet->setCellValue(chr(65 + ++$c) . "$row", $detail['keanggotaan']);
                $sheet->setCellValue(chr(65 + ++$c) . "$row", $detail['alamat']);
                $sheet->setCellValue(chr(65 + ++$c) . "$row", $detail['wilayah']);
                $sheet->setCellValue(chr(65 + ++$c) . "$row", $detail['kategorial']);
                $sheet->setCellValue(chr(65 + ++$c) . "$row", $kontak1);
                $sheet->setCellValue(chr(65 + ++$c) . "$row", $kontak2);
                $sheet->setCellValue(chr(65 + ++$c) . "$row", $kontak3);
                $sheet->setCellValue(chr(65 + ++$c) . "$row", $dataformal1);
                $sheet->setCellValue(chr(65 + ++$c) . "$row", $dataformal2);
                $sheet->setCellValue(chr(65 + ++$c) . "$row", $dataformal3);
                $row++;
            }
        }
        // format
        // nomor center
        // $sheet->getStyle($col_start . $start_tabel . ":" . $col_start . $row)
        //     ->getAlignment()
            // ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        // border all data
        $sheet->getStyle($col_start . $start_tabel . ":" . $col_end . $row)
            ->applyFromArray($styleArray);

        // $code_rm = '_-[$RM-ms-MY]* #.##0,00_-;-[$RM-ms-MY]* #.##0,00_-;_-[$RM-ms-MY]* "-"??_-;_-@_-';
        // $sheet->getStyle("F" . $start_tabel . ":" . $col_end . $row)->getNumberFormat()->setFormatCode($code_rm);

        // $sheet->getStyle("F" . $start_tabel . ":" . $col_end . $row)->getNumberFormat()->setFormatCode($code_rm);

        // set alignment
        // $sheet->getStyle("A" . $start_tabel . ":" . "A" . $row)->getAlignment()->setHorizontal('center');
        // $sheet->getStyle("B" . $start_tabel . ":" . "B" . $row)->getAlignment()->setHorizontal('center');
        // $sheet->getStyle("C" . $start_tabel . ":" . "C" . $row)->getAlignment()->setHorizontal('center');
        // $sheet->getStyle("C" . $start_tabel . ":D" . $row)
        //     ->getAlignment()
        //     ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);

        // footer
        // $row += 3;
        // $sheet->setCellValue("Q" . $row, "Kasui, $date");

        // $row += 3;
        // $sheet->setCellValue("Q" . $row, "(.....................................)");

        // function for width column
        function w($width)
        {
            return 0.71 + $width;
        }


        // set width column
        $spreadsheet->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('M')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('N')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('O')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('P')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('Q')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('R')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('S')->setAutoSize(true);
        // set  printing area
        $spreadsheet->getActiveSheet()->getPageSetup()->setPrintArea($col_start . '1:' . $col_end . $row);
        $spreadsheet->getActiveSheet()->getPageSetup()
            ->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_PORTRAIT);
        $spreadsheet->getActiveSheet()->getPageSetup()
            ->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);

        // margin
        $spreadsheet->getActiveSheet()->getPageMargins()->setTop(1);
        $spreadsheet->getActiveSheet()->getPageMargins()->setRight(0);
        $spreadsheet->getActiveSheet()->getPageMargins()->setLeft(0);
        $spreadsheet->getActiveSheet()->getPageMargins()->setBottom(0);

        // page center on
        $spreadsheet->getActiveSheet()->getPageSetup()->setHorizontalCentered(true);
        $spreadsheet->getActiveSheet()->getPageSetup()->setVerticalCentered(false);

        // $writer = new PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        // $writer->save($title_excel);
        // header("Location: " . base_url($title_excel));
        $writer = new PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);


        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $title_excel . '.xlsx"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
    }

    public function grouping()
    {

        $bulan_array = [
            1 => 'Januari',
            2 => 'February',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember',
        ];
        $today_m = (int)Date("m");
        $today_d = (int)Date("d");
        $today_y = (int)Date("Y");

        $last_date_of_this_month =  date('t', strtotime(date("Y-m-d")));

        $date = $today_d . " " . $bulan_array[$today_m] . " " . $today_y;

        // laporan baru
        $row = 1;
        $col_start = "A";
        $col_end = "M";
        $title_excel = "CmMS Member - Grouping";
        // Header excel ================================================================================================
        $spreadsheet = new PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Dokumen Properti
        $spreadsheet->getProperties()
            ->setCreator("Audit System End to End")
            ->setLastModifiedBy("Administrator")
            ->setTitle($title_excel)
            ->setSubject("Administrator")
            ->setDescription("List Profile $date")
            ->setKeywords("Laporan, Report")
            ->setCategory("Laporan, Report");
        // set default font
        $spreadsheet->getDefaultStyle()->getFont()->setName('Calibri');
        $spreadsheet->getDefaultStyle()->getFont()->setSize(11);


        // header 2 ====================================================================================================
        $row += 1;
        $sheet->mergeCells($col_start . $row . ":" . $col_end . $row)
            ->setCellValue("A$row", "" . $this->getMenuTitle());
        $sheet->getStyle($col_start . $row . ":" . $col_end . $row)->applyFromArray([
            "font" => [
                "bold" => true,
                "size" => 13
            ],
            "alignment" => [
                "horizontal" => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
        ]);

        // Tabel =======================================================================================================
        // Tabel Header
        $row += 2;
        $styleArray = [
            'font' => [
                'bold' => true,
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => [
                    'rgb' => '93C5FD',
                ]
            ],
        ];
        $sheet->getStyle($col_start . $row . ":" . $col_end . $row)->applyFromArray($styleArray);
        $row++;
        $styleArray['fill']['startColor']['rgb'] = 'E5E7EB';
        $sheet->getStyle($col_start . $row . ":" . $col_end . $row)->applyFromArray($styleArray);

        // poin-poin header disini
        $headers = [
            'No',
            'Kode',
            'Nama Lengkap',
            'Jenis Kelamin',
            'Photo',
            'Flag',
            'Tanggal Registrasi',
            'Tanggal Lahir',
            'Tanggal Meninggal',
            'Keanggotaan',
            'Kategorial',
            'Wilayah',
            'Tanggal',
        ];

        // apply header
        for ($i = 0; $i < count($headers); $i++) {
            $sheet->setCellValue(chr(65 + $i) . ($row - 1), $headers[$i]);
            $sheet->setCellValue(chr(65 + $i) . $row, ($i + 1));
        }

        // tabel body
        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '000000'],
                ],
            ],
            "alignment" => [
                'wrapText' => TRUE,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP
            ]
        ];
        $start_tabel = $row + 1;
        $partner = $this->model->getPartner();
        foreach ($partner as $pt) {
            $details = $this->model->getGrouping($pt['id']);
            $row++;
            $c = 0;
            $sheet->getStyle($col_start . $row . ":" . $col_end . $row)->applyFromArray([
                "font" => [
                    "bold" => true,
                    "size" => 12
                ]
            ]);

            $sheet->mergeCells($col_start . $row . ":" . $col_end . $row)->setCellValue("A$row", $pt['nama']);
            $sheet->setCellValue(chr(65 + ++$c) . "$row", $pt['nama']);
            $sheet->setCellValue(chr(65 + ++$c) . "$row", "");
            $sheet->setCellValue(chr(65 + ++$c) . "$row", "");
            $sheet->setCellValue(chr(65 + ++$c) . "$row", "");
            $sheet->setCellValue(chr(65 + ++$c) . "$row", "");
            $sheet->setCellValue(chr(65 + ++$c) . "$row", "");
            $sheet->setCellValue(chr(65 + ++$c) . "$row", "");
            $sheet->setCellValue(chr(65 + ++$c) . "$row", "");
            $sheet->setCellValue(chr(65 + ++$c) . "$row", "");
            $sheet->setCellValue(chr(65 + ++$c) . "$row", "");
            $sheet->setCellValue(chr(65 + ++$c) . "$row", "");
            $sheet->setCellValue(chr(65 + ++$c) . "$row",  "");
            $nomor = 0;
            if(count($details) < 1){
                $row++;
                $sheet->mergeCells($col_start . $row . ":" . $col_end . $row)->setCellValue("A$row", "Belum ada data member dipartner ini");
            }else{
                foreach ($details as $detail) {
                    $c = 0;
                    $tanggal = is_null($detail['updated_at']) || $detail['updated_at'] == '' ?  $detail['created_at'] :  $detail['updated_at'];
                    $tanggal = date("d-m-Y", strtotime($tanggal));
                    if($detail['kode'] != null){
                        $row++;
                        if($detail['keanggotaan_nama'] != ""){
                            $keanggotaan = $detail['keanggotaan_nama']. " (".$detail['keanggotaan_inisial'].")";
                        }else{
                            $keanggotaan = "";
                        }
                        $nomor++;
                        $sheet->setCellValue(chr(65 + $c) . "$row", $nomor);
                        $sheet->setCellValue(chr(65 + ++$c) . "$row", $detail['kode']);
                        $sheet->setCellValue(chr(65 + ++$c) . "$row", $detail['nama_depan']." ".$detail['nama_belakang']);
                        $sheet->setCellValue(chr(65 + ++$c) . "$row", $detail['jenis_kelamin']);
                        $sheet->setCellValue(chr(65 + ++$c) . "$row", $detail['photo']);
                        $sheet->setCellValue(chr(65 + ++$c) . "$row", $detail['flag']);
                        $sheet->setCellValue(chr(65 + ++$c) . "$row", $detail['tanggal_registrasi']);
                        $sheet->setCellValue(chr(65 + ++$c) . "$row", $detail['tanggal_lahir']);
                        $sheet->setCellValue(chr(65 + ++$c) . "$row", $detail['tanggal_meninggal']);
                        $sheet->setCellValue(chr(65 + ++$c) . "$row", $keanggotaan);
                        $sheet->setCellValue(chr(65 + ++$c) . "$row", $detail['kategorial']);
                        $sheet->setCellValue(chr(65 + ++$c) . "$row", $detail['wilayah']);
                        $sheet->setCellValue(chr(65 + ++$c) . "$row",  $tanggal);
                    }
                }
            }
            $c = 0;
        }
        // format
        // nomor center
        $sheet->getStyle($col_start . $start_tabel . ":" . $col_start . $row)
            ->getAlignment()
            ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        // border all data
        $sheet->getStyle($col_start . $start_tabel . ":" . $col_end . $row)
            ->applyFromArray($styleArray);

        // $code_rm = '_-[$RM-ms-MY]* #.##0,00_-;-[$RM-ms-MY]* #.##0,00_-;_-[$RM-ms-MY]* "-"??_-;_-@_-';
        // $sheet->getStyle("F" . $start_tabel . ":" . $col_end . $row)->getNumberFormat()->setFormatCode($code_rm);

        // $sheet->getStyle("F" . $start_tabel . ":" . $col_end . $row)->getNumberFormat()->setFormatCode($code_rm);

        // set alignment
        $sheet->getStyle("A" . $start_tabel . ":" . "A" . $row)->getAlignment()->setHorizontal('center');
        // $sheet->getStyle("B" . $start_tabel . ":" . "B" . $row)->getAlignment()->setHorizontal('center');
        // $sheet->getStyle("C" . $start_tabel . ":" . "C" . $row)->getAlignment()->setHorizontal('center');
        // $sheet->getStyle("C" . $start_tabel . ":D" . $row)
        //     ->getAlignment()
        //     ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);

        // footer
        // $row += 3;
        // $sheet->setCellValue("Q" . $row, "Kasui, $date");

        // $row += 3;
        // $sheet->setCellValue("Q" . $row, "(.....................................)");

        // function for width column
        function w($width)
        {
            return 0.71 + $width;
        }


        // set width column
        $spreadsheet->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('M')->setAutoSize(true);
        // set  printing area
        $spreadsheet->getActiveSheet()->getPageSetup()->setPrintArea($col_start . '1:' . $col_end . $row);
        $spreadsheet->getActiveSheet()->getPageSetup()
            ->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_PORTRAIT);
        $spreadsheet->getActiveSheet()->getPageSetup()
            ->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);

        // margin
        $spreadsheet->getActiveSheet()->getPageMargins()->setTop(1);
        $spreadsheet->getActiveSheet()->getPageMargins()->setRight(0);
        $spreadsheet->getActiveSheet()->getPageMargins()->setLeft(0);
        $spreadsheet->getActiveSheet()->getPageMargins()->setBottom(0);

        // page center on
        $spreadsheet->getActiveSheet()->getPageSetup()->setHorizontalCentered(true);
        $spreadsheet->getActiveSheet()->getPageSetup()->setVerticalCentered(false);

        // $writer = new PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        // $writer->save($title_excel);
        // header("Location: " . base_url($title_excel));
        $writer = new PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);


        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $title_excel . '.xlsx"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
    }

    public function multi()
    {

        $details = $this->model->getMulti();


        $bulan_array = [
            1 => 'Januari',
            2 => 'February',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember',
        ];
        $today_m = (int)Date("m");
        $today_d = (int)Date("d");
        $today_y = (int)Date("Y");

        $last_date_of_this_month =  date('t', strtotime(date("Y-m-d")));

        $date = $today_d . " " . $bulan_array[$today_m] . " " . $today_y;

        // laporan baru
        $row = 1;
        $col_start = "A";
        $col_end = "P";
        $title_excel = "CmMS Member - Multi";
        // Header excel ================================================================================================
        $spreadsheet = new PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Dokumen Properti
        $spreadsheet->getProperties()
            ->setCreator("Audit System End to End")
            ->setLastModifiedBy("Administrator")
            ->setTitle($title_excel)
            ->setSubject("Administrator")
            ->setDescription("List Profile $date")
            ->setKeywords("Laporan, Report")
            ->setCategory("Laporan, Report");
        // set default font
        $spreadsheet->getDefaultStyle()->getFont()->setName('Calibri');
        $spreadsheet->getDefaultStyle()->getFont()->setSize(11);


        // header 2 ====================================================================================================
        $row += 1;
        $sheet->mergeCells($col_start . $row . ":" . $col_end . $row)
            ->setCellValue("A$row", "" . $this->getMenuTitle());
        $sheet->getStyle($col_start . $row . ":" . $col_end . $row)->applyFromArray([
            "font" => [
                "bold" => true,
                "size" => 13
            ],
            "alignment" => [
                "horizontal" => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
        ]);

        // Tabel =======================================================================================================
        // Tabel Header
        $row += 2;
        $styleArray = [
            'font' => [
                'bold' => true,
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => [
                    'rgb' => '93C5FD',
                ]
            ],
        ];
        $sheet->getStyle($col_start . $row . ":" . $col_end . $row)->applyFromArray($styleArray);
        $row++;
        $styleArray['fill']['startColor']['rgb'] = 'E5E7EB';
        $sheet->getStyle($col_start . $row . ":" . $col_end . $row)->applyFromArray($styleArray);

        // poin-poin header disini
        $headers = [
            'No',
            'Kode',
            'Nama Lengkap',
            'Jenis Kelamin',
            'Flag',
            'Tanggal Registrasi',
            'Tanggal Lahir',
            'Tanggal Meninggal',
            'Tanggal',
            'Peristiwa',
            'Institusi',
            'Jabatan Pengesah',
            'Keterangan',
            'Tanggal Event',
            'Tempat',
            'Photo',
        ];
        for ($i = 0; $i < count($headers); $i++) {
            $sheet->setCellValue(chr(65 + $i) . ($row - 1), $headers[$i]);
            $sheet->setCellValue(chr(65 + $i) . $row, ($i + 1));
        }
        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '000000'],
                ],
            ],
            "alignment" => [
                'wrapText' => TRUE,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP
            ]
        ];
        $start_tabel = $row + 1;
        foreach ($details as $detail) {
            $c = 0;
            $tanggal = is_null($detail['updated_at']) || $detail['updated_at'] == '' ?  $detail['created_at'] :  $detail['updated_at'];
            $tanggal = date("d-m-Y", strtotime($tanggal));
            if($detail['kode'] != null){
                $row++;
                
                $sheet->setCellValue(chr(65 + $c) . "$row", ($row - 5));
                $sheet->setCellValue(chr(65 + ++$c) . "$row", $detail['kode']);
                $sheet->setCellValue(chr(65 + ++$c) . "$row", $detail['nama_depan']." ".$detail['nama_belakang']);
                $sheet->setCellValue(chr(65 + ++$c) . "$row", $detail['jenis_kelamin']);
                $sheet->setCellValue(chr(65 + ++$c) . "$row", $detail['flag']);
                $sheet->setCellValue(chr(65 + ++$c) . "$row", $detail['tanggal_registrasi']);
                $sheet->setCellValue(chr(65 + ++$c) . "$row", $detail['tanggal_lahir']);
                $sheet->setCellValue(chr(65 + ++$c) . "$row", $detail['tanggal_meninggal']);
                $sheet->setCellValue(chr(65 + ++$c) . "$row", $tanggal);
                $sheet->setCellValue(chr(65 + ++$c) . "$row", $detail['peristiwa']);
                $sheet->setCellValue(chr(65 + ++$c) . "$row", $detail['institusi']);
                $sheet->setCellValue(chr(65 + ++$c) . "$row", $detail['jabatan_nama_depan']." ".$detail['jabatan_nama_belakang']);
                $sheet->setCellValue(chr(65 + ++$c) . "$row", $detail['keterangan']);
                $sheet->setCellValue(chr(65 + ++$c) . "$row", $detail['tanggal_data_formal']);
                $sheet->setCellValue(chr(65 + ++$c) . "$row", $detail['tempat']);
                $sheet->setCellValue(chr(65 + ++$c) . "$row", $detail['foto']);
            }
        }
        // format
        // nomor center
        $sheet->getStyle($col_start . $start_tabel . ":" . $col_start . $row)
            ->getAlignment()
            ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        // border all data
        $sheet->getStyle($col_start . $start_tabel . ":" . $col_end . $row)
            ->applyFromArray($styleArray);

        // $code_rm = '_-[$RM-ms-MY]* #.##0,00_-;-[$RM-ms-MY]* #.##0,00_-;_-[$RM-ms-MY]* "-"??_-;_-@_-';
        // $sheet->getStyle("F" . $start_tabel . ":" . $col_end . $row)->getNumberFormat()->setFormatCode($code_rm);

        // $sheet->getStyle("F" . $start_tabel . ":" . $col_end . $row)->getNumberFormat()->setFormatCode($code_rm);

        // set alignment
        $sheet->getStyle("A" . $start_tabel . ":" . "A" . $row)->getAlignment()->setHorizontal('center');
        // $sheet->getStyle("B" . $start_tabel . ":" . "B" . $row)->getAlignment()->setHorizontal('center');
        // $sheet->getStyle("C" . $start_tabel . ":" . "C" . $row)->getAlignment()->setHorizontal('center');
        // $sheet->getStyle("C" . $start_tabel . ":D" . $row)
        //     ->getAlignment()
        //     ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);

        // footer
        // $row += 3;
        // $sheet->setCellValue("Q" . $row, "Kasui, $date");

        // $row += 3;
        // $sheet->setCellValue("Q" . $row, "(.....................................)");

        // function for width column
        function w($width)
        {
            return 0.71 + $width;
        }


        // set width column
        $spreadsheet->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('M')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('N')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('O')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('P')->setAutoSize(true);
        // set  printing area
        $spreadsheet->getActiveSheet()->getPageSetup()->setPrintArea($col_start . '1:' . $col_end . $row);
        $spreadsheet->getActiveSheet()->getPageSetup()
            ->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_PORTRAIT);
        $spreadsheet->getActiveSheet()->getPageSetup()
            ->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);

        // margin
        $spreadsheet->getActiveSheet()->getPageMargins()->setTop(1);
        $spreadsheet->getActiveSheet()->getPageMargins()->setRight(0);
        $spreadsheet->getActiveSheet()->getPageMargins()->setLeft(0);
        $spreadsheet->getActiveSheet()->getPageMargins()->setBottom(0);

        // page center on
        $spreadsheet->getActiveSheet()->getPageSetup()->setHorizontalCentered(true);
        $spreadsheet->getActiveSheet()->getPageSetup()->setVerticalCentered(false);

        // $writer = new PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        // $writer->save($title_excel);
        // header("Location: " . base_url($title_excel));
        $writer = new PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);


        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $title_excel . '.xlsx"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
    }

    public function import_excel()
    {
        // Fungsi upload file
        $fileName = $_FILES['file']['name'];
        $config['upload_path'] = './assets/'; //path upload
        $config['file_name'] = $fileName;  // nama file
        $config['allowed_types'] = 'xls|xlsx'; //tipe file yang diperbolehkan
        $config['max_size'] = 100000; // maksimal sizze

        $this->load->library('upload'); //meload librari upload
        $this->upload->initialize($config);

        $file_location = "";

        if (!$this->upload->do_upload('file')) {
            echo json_encode(['code' => 1, 'message' => $this->upload->display_errors()]);

            exit();
        } else {
            $file_location = array('upload_data' => $this->upload->data());
            $file_location = $file_location['upload_data']['full_path'];
        }

        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file_location);
        // hapus file setelah digunakan
        unlink($file_location);
        $array_from_excel = $spreadsheet->getActiveSheet()->toArray();
        $num = 1;
        $no_resi1 = '';
        $error_message = '';
        $kode_penjualan = '';
        // var_dump($array_from_excel);
        // die;
        // get kode admin by session id_user

        $no_resi = [];
        $this->db->trans_start();
        foreach ($array_from_excel as $val) {
            if ($num > 1) {
                $keluarga = $val[0];
                if($keluarga == "" or $keluarga == null or $keluarga == "null"){
                    $keluarga = "";
                    $id_keluarga = 0;
                }else{
                    $cek_keluarga = $this->db->get_where('partner',['nama' => $keluarga])->row_array();
                    if($cek_keluarga == null){
                        $save_keluarga['nama'] = $keluarga;
                        $save_keluarga['status'] = 1;
                        $save_keluarga['created_at'] = date("Y-m-d");
                        $this->db->insert("partner", $save_keluarga);
                        $id_keluarga = $this->db->insert_id();
                    }else{
                        $id_keluarga = $cek_keluarga['id'];
                    }
                }
                
                $posisi = $val[1];
                if($posisi == "" or $posisi == null or $posisi == "null"){
                    $posisi = "";
                    $id_posisi = 0;
                }else{
                    $cek_posisi = $this->db->get_where('posisi',['nama' => $posisi])->row_array();
                    if($cek_posisi == null){
                        $save_posisi['nama'] = $posisi;
                        $save_posisi['status'] = 1;
                        $save_posisi['created_at'] = date("Y-m-d H:i:s");
                        $this->db->insert("posisi", $save_posisi);
                        $id_posisi = $this->db->insert_id();
                    }else{
                        $id_posisi = $cek_posisi['id'];
                    }
                }
                
                $nama_belakang = $val[2];
                if($nama_belakang == "" or $nama_belakang == null or $nama_belakang == "null"){
                    $nama_belakang = "";
                }
                $nama_depan = $val[3];
                if($nama_depan == "" or $nama_depan == null or $nama_depan == "null"){
                    $nama_depan = "";
                }
                $tanggal_registrasi = $val[4];
                if($tanggal_registrasi != null or $tanggal_registrasi != ""){
                    $tanggal_registrasi = date("Y-m-d", strtotime($tanggal_registrasi));    
                }else{
                    $tanggal_registrasi = "0000-00-00";
                }
                
                $tanggal_lahir = $val[5];
                if($tanggal_lahir != null or $tanggal_lahir != ""){
                    $tanggal_lahir = date("Y-m-d", strtotime($tanggal_lahir));    
                }else{
                    $tanggal_lahir = "0000-00-00";
                }
                $tanggal_lahir = date("Y-m-d", strtotime($tanggal_lahir));

                $tanggal_meninggal = $val[6];
                if($tanggal_meninggal != null or $tanggal_meninggal != ""){
                    $tanggal_meninggal = date("Y-m-d", strtotime($tanggal_meninggal));    
                }else{
                    $tanggal_meninggal = "0000-00-00";
                }
                
                $jenis_kelamin = $val[7];
                if ($jenis_kelamin == "" or $jenis_kelamin == null or $jenis_kelamin == "null") {
                    $jenis_kelamin = 0;
                } else if ($jenis_kelamin == "Pria") {
                    $jenis_kelamin = 1;
                } else if ($jenis_kelamin == "Wanita") {
                    $jenis_kelamin = 2;
                } else {
                    $jenis_kelamin = 0;
                }

                $keanggotaan = $val[8];
                if($keanggotaan == "" or $keanggotaan == null or $keanggotaan == "null"){
                    $keanggotaan = "";
                    $id_keanggotaan = 0;
                    $kode_keanggotaan = "";
                }else{
                    $cek_keanggotaan = $this->db->get_where('kategori_keanggotaan',['keterangan' => $keanggotaan])->row_array();
                    if($cek_keanggotaan == null){
                        $save_keanggotaan['nama'] = $keanggotaan;
                        $save_keanggotaan['keterangan'] = $keanggotaan;
                        $save_keanggotaan['status'] = 1;
                        $save_keanggotaan['created_at'] = date("Y-m-d H:i:s");
                        $this->db->insert("kategori_keanggotaan", $save_keanggotaan);
                        $id_keanggotaan = $this->db->insert_id();
                        $kode_keanggotaan = $keterangan;
                    }else{
                        $id_keanggotaan = $cek_keanggotaan['id'];
                        $kode_keanggotaan = $cek_keanggotaan['nama'];
                    }
                }
                
                $alamat = $val[9];
                if($alamat == "" or $alamat == null or $alamat == "null"){
                    $alamat = "";
                }
            
                $wilayah = $val[10];
                if($wilayah == "" or $wilayah == null or $wilayah == "null"){
                    $wilayah = "";
                    $id_wilayah = 0;
                }else{
                    $cek_wilayah = $this->db->get_where('wilayah',['nama' => $wilayah])->row_array();
                    if($cek_wilayah == null){
                        $save_wilayah['nama'] = $wilayah;
                        $save_wilayah['status'] = 1;
                        $save_wilayah['created_at'] = date("Y-m-d H:i:s");
                        $this->db->insert("wilayah", $save_wilayah);
                        $id_wilayah = $this->db->insert_id();
                    }else{
                        $id_wilayah = $cek_wilayah['id'];
                    }
                }
                
                $kategorial = $val[11];
                if($kategorial == "" or $kategorial == null or $kategorial == "null"){
                    $kategorial = "";
                    $id_kategorial = 0;
                }else{
                    $cek_kategorial = $this->db->get_where('kategorial',['nama' => $kategorial])->row_array();
                    if($cek_kategorial == null){
                        $save_kategorial['nama'] = $kategorial;
                        $save_kategorial['status'] = 1;
                        $save_kategorial['created_at'] = date("Y-m-d H:i:s");
                        $this->db->insert("kategorial", $save_kategorial);
                        $id_kategorial = $this->db->insert_id();
                    }else{
                        $id_kategorial = $cek_kategorial['id'];
                    }
                }
                
                $telepon = $val[12];
                if($telepon == "" or $telepon == null or $telepon == "null"){
                    $telepon = "";
                }
                $mobile = $val[13];
                if($mobile == "" or $mobile == null or $mobile == "null"){
                    $mobile = "";
                }
                $email = $val[14];
                if($email == "" or $email == null or $email == "null"){
                    $email = "";
                }
                $peristiwa_1 = $val[15];
                if($peristiwa_1 != null or $peristiwa_1 != ""){
                    $peristiwa_1 = date("Y-m-d", strtotime($peristiwa_1));    
                }else{
                    $peristiwa_1 = "0000-00-00";
                }
                
                $peristiwa_2 = $val[16];
                if($peristiwa_2 != null or $peristiwa_2 != ""){
                    $peristiwa_2 = date("Y-m-d", strtotime($peristiwa_2));    
                }else{
                    $peristiwa_2 = "0000-00-00";
                }
                
                $peristiwa_3 = $val[17];
                if($peristiwa_3 != null or $peristiwa_3 != ""){
                    $peristiwa_3 = date("Y-m-d", strtotime($peristiwa_3));    
                }else{
                    $peristiwa_3 = "0000-00-00";
                }
                $get_kode = $this->getCodeKeluarga($tanggal_registrasi, $id_keanggotaan);
                $nik = $get_kode['kode'];
                $kode_tahun_bulan = $get_kode['tahun_bulan'];
                
                $cek_profile = $this->db->get_where('profile',['nama_belakang' => $nama_belakang, 'nama_depan' => $nama_depan, 'tanggal_registrasi' => $tanggal_registrasi, 'tanggal_lahir' => $tanggal_lahir])->row_array();
                if($id_keluarga != null){

                    if($cek_profile == null){

                        $tbh = $this->db->insert('users', [
                            "id_partner"   => $id_keluarga,
                            "nik"   => $nik,
                            "id_posisi" => $id_posisi,
                            "user_nama"  => $nama_depan . " " . $nama_belakang,
                            "user_email"  => $email,
                            "user_email_status"  => 1,
                            "user_phone"  => is_int($mobile) == "" ? 0 : $mobile,
                            "user_password"  => $this->b_password->bcrypt_hash('123456'),
                            "user_status"         => 1,
                            "created_at"    => date("Y-m-d H:i:s")
                        ]);
                        $id_user = $this->db->insert_id();
                        
                        
                        $level = 123;
                        $tbh = $this->db->insert('role_users', [
                            "role_user_id "   => $id_user,
                            "role_lev_id"   => $level
                        ]);

                        
                        $tbh = $this->db->insert('profile', [
                            "id_user"   => $id_user,
                            "nama_depan"   => $nama_depan,
                            "nama_belakang"  => $nama_belakang,
                            "jenis_kelamin"  => $jenis_kelamin,
                            "tanggal_registrasi" => $tanggal_registrasi,
                            "tanggal_lahir" => $tanggal_lahir,
                            "tanggal_meninggal" => $tanggal_meninggal,
                            "id_keanggotaan" => $id_keanggotaan,
                            "id_wilayah" => $id_wilayah,
                            "id_kategorial" => $id_kategorial,
                            "kode" => $nik,
                            "kode_tahun_bulan" => $kode_tahun_bulan,
                            "kode_keanggotaan" => $kode_keanggotaan,
                            "flag" => 1,
                            "status"         => 1,
                            "created_at"    => date("Y-m-d")
                        ]);
                        $id_profile = $this->db->insert_id();
            
                        $tbh = $this->db->insert('profile_keanggotaan', [
                            "id_profile "   => $id_profile,
                            "id_kategori_keanggotaan"   => $id_keanggotaan,
                            "tanggal"   => date("Y-m-d"),
                            "status"         => 2,
                            "created_at"    => date("Y-m-d H:i:s")
                        ]);
                        
                        $tbh = $this->db->insert('profile_wilayah', [
                            "id_profile "   => $id_profile,
                            "id_wilayah"   => $id_wilayah,
                            "tanggal"   => date("Y-m-d"),
                            "status"         => 1,
                            "created_at"    => date("Y-m-d H:i:s")
                        ]);
                        
                        $tbh = $this->db->insert('profile_kategorial', [
                            "id_profile "   => $id_profile,
                            "id_kategorial"   => $id_kategorial,
                            "tanggal"   => date("Y-m-d"),
                            "status"         => 1,
                            "created_at"    => date("Y-m-d H:i:s")
                        ]);
                        
                        if($telepon != "" or $telepon != null){
                            $id_telepon = 3;
                            $tbh = $this->db->insert('contact', [
                                "id_profile "   => $id_profile,
                                "id_tipe_contact"   => $id_telepon,
                                "tanggal" => date("Y-m-d"),
                                "keterangan"  => $telepon,
                                "status"         => 1,
                                "created_at"    => date("Y-m-d")
                            ]);    
                        }

                        if($mobile != "" or $mobile != null){
                            $id_mobile = 2;
                            $tbh = $this->db->insert('contact', [
                                "id_profile "   => $id_profile,
                                "id_tipe_contact"   => $id_mobile,
                                "tanggal" => date("Y-m-d"),
                                "keterangan"  => $mobile,
                                "status"         => 1,
                                "created_at"    => date("Y-m-d")
                            ]);    
                        }
                        
                        if($email != "" or $email != null){
                            $id_email = 1;
                            $tbh = $this->db->insert('contact', [
                                "id_profile "   => $id_profile,
                                "id_tipe_contact"   => $id_email,
                                "tanggal" => date("Y-m-d"),
                                "keterangan"  => $email,
                                "status"         => 1,
                                "created_at"    => date("Y-m-d")
                            ]);    
                        }
                        
                        $id_jenis_alamat = 2; // default domisili
                        $tbh = $this->db->insert('alamat', [
                            "id_profile "   => $id_profile,
                            "id_jenis_alamat"   =>$id_jenis_alamat,
                            "alamat"  => $alamat,
                            "domisili"  => $alamat,
                            "tanggal_mulai"  => date("Y-m-d"),
                            "tanggal_selesai"  => null,
                            "status"         => 1,
                            "created_at"    => date("Y-m-d")
                        ]);

                        if($peristiwa_1 != "" or $peristiwa_1 != null or $peristiwa_1 != "0000-00-00"){
                            $kode = $this->getCodeDataFormal();
                            $kode = $kode['id'];
                            $id_peristiwa_1 = 2;
                            $save_data_formal_1 = $this->db->insert('data_formal', [
                                "kode" => $kode,
                                "kode_tahun_bulan" => date("Ym"),
                                "id_profile "   => $id_profile,
                                "dtm_peristiwa_id"   => $id_peristiwa_1,
                                "tanggal_data_formal"   => $peristiwa_1,
                                "status"         => 1,
                                "created_at"    => date("Y-m-d H:i:s")
                            ]);    
                            $id_data_formal_1 = $this->db->insert_id();
                            if($save_data_formal_1){
                                $save_data_formal_detail_1  = $this->db->insert('data_formal_detail', [
                                    "id_data_formal" => $id_data_formal_1,
                                    "id_profile" => $id_profile,
                                    "status" => 1,
                                    "created_at" => date("Y-m-d H:i:s")
                                ]);
                            }
                        }

                        if($peristiwa_2 != "" or $peristiwa_2 != null or $peristiwa_2 != "0000-00-00"){
                            $kode = $this->getCodeDataFormal();
                            $kode = $kode['id'];
                            $id_peristiwa_2 = 3;
                            $save_data_formal_2 = $this->db->insert('data_formal', [
                                "kode" => $kode,
                                "kode_tahun_bulan" => date("Ym"),
                                "id_profile "   => $id_profile,
                                "dtm_peristiwa_id"   => $id_peristiwa_2,
                                "tanggal_data_formal"   => $peristiwa_2,
                                "status"         => 1,
                                "created_at"    => date("Y-m-d H:i:s")
                            ]);  
                            $id_data_formal_2 = $this->db->insert_id();
                            if($save_data_formal_2){
                                $save_data_formal_detail_2  = $this->db->insert('data_formal_detail', [
                                    "id_data_formal" => $id_data_formal_2,
                                    "id_profile" => $id_profile,
                                    "status" => 1,
                                    "created_at" => date("Y-m-d H:i:s")
                                ]);
                            }  
                        }

                        if($peristiwa_3 != "" or $peristiwa_3 != null or $peristiwa_3 != "0000-00-00"){
                            $kode = $this->getCodeDataFormal();
                            $kode = $kode['id'];
                            $id_peristiwa_3 = 7; //pernikahan
                            $save_data_formal_3 = $this->db->insert('data_formal', [
                                "kode" => $kode,
                                "kode_tahun_bulan" => date("Ym"),
                                "id_profile "   => $id_profile,
                                "dtm_peristiwa_id"   => $id_peristiwa_3,
                                "tanggal_data_formal"   => $peristiwa_3,
                                "status"         => 1,
                                "created_at"    => date("Y-m-d H:i:s")
                            ]);    
                            $id_data_formal_2 = $this->db->insert_id();
                            if($save_data_formal_2){
                                $save_data_formal_detail_2  = $this->db->insert('data_formal_detail', [
                                    "id_data_formal" => $id_data_formal_2,
                                    "id_profile" => $id_profile,
                                    "status" => 1,
                                    "created_at" => date("Y-m-d H:i:s")
                                ]);
                            }  
                        }
                    }
                }
            }
            $num++;
        }
    


        // database transaction complete
        $this->db->trans_complete();
        $code = $tbh ? 200 : 500;
        $this->output_json(["data" => $tbh], $code);
    }

    public function getCodeDataFormal(){
        $bulan = $this->input->post('bulan');
        $tahun = $this->input->post('tahun');

        $id_menu = 82;
        $id_kategori = 11; // pengunjung
        $get = $this->db->select('a.*, b.nama as kode_kategori')
                    ->join('kategori_p2pk b', 'b.id = a.id_kategori')
                    ->get_where('kode a', ['a.status' => 1, 'b.id' => $id_kategori])
                    ->row_array();
        $jumlah = $get['jumlah'];

        $cek_tahun_bulan = $this->db->get_where('data_formal', ['kode_tahun_bulan' => $tahun.$bulan])->row_array();
        if($cek_tahun_bulan == null){
            $kode = 1;
        }else{
            $this->db->select('RIGHT(data_formal.kode,'.$jumlah.') as id', FALSE);
            $this->db->order_by('id','DESC');
            $this->db->where('kode_tahun_bulan',$tahun.$bulan);
            $this->db->limit(1);    
            $query = $this->db->get('data_formal');  //cek dulu apakah ada sudah ada kode di tabel.    
            if($query->num_rows() <> 0){      
               $data = $query->row();      
               $kode = intval($data->id) + 1; 
            }
            else{      
               $kode = 1; 
            }
        }    
        $batas = str_pad($kode, $jumlah, "0", STR_PAD_LEFT);    
        
        if($bulan == null){
            $bulan = "";
        }

        if($tahun == null){
            $tahun = "";
        }
        // $kodetampil = $get['kode']."-".$tahun.$bulan."-".$batas.$get['kode_kategori'];
        $kodetampil = $get['kode']."-".$tahun.$bulan."-".$batas;
          
        $output['id'] = $kodetampil;
        $output['kode'] = $kode;
        $output['jumlah'] = $jumlah;
        $output['cek_tahun_bulan'] = $cek_tahun_bulan;
        return $output;
    }

    public function getCodeKeluarga($tanggal_registrasi=null, $id_keanggotaan=null){
        $bulan =  date('m', strtotime($tanggal_registrasi));
        $tahun =  date('Y', strtotime($tanggal_registrasi));
        
        $id_menu = 2;
        if($$id_keanggotaan = null){
            $id_keanggotaan = 1;
        }
        $id_menu = 2;
        $get = $this->db->select('a.*, b.nama as kode_kategori')
                    ->join('kategori_keanggotaan b', 'b.id = a.id_kategori')
                    ->get_where('kode a', ['a.status' => 1, 'b.id' => $id_keanggotaan])
                    ->row_array();
        $jumlah = $get['jumlah'];
        
        $cek_tahun_bulan = $this->db->get_where('profile', ['kode_tahun_bulan' => $tahun.$bulan])->row_array();
        if($cek_tahun_bulan == null){
            $kode = 1;
        }else{
            $this->db->select('RIGHT(profile.kode,'.$jumlah.') as id', FALSE);
            $this->db->where('kode_tahun_bulan',$tahun.$bulan);
            $this->db->order_by('id','DESC');
            $this->db->limit(1);    
            $query = $this->db->get('profile');  //cek dulu apakah ada sudah ada kode di tabel.    
            if($query->num_rows() <> 0){      
               $data = $query->row();      
               $kode = intval($data->id) + 1; 
            }
            else{      
               $kode = 1; 
            }
        }

        $batas = str_pad($kode, $jumlah, "0", STR_PAD_LEFT);    
        
        if($bulan == null){
            $bulan = "";
        }

        if($tahun == null){
            $tahun = "";
        }

        $kodetampil = $get['kode']."-".$tahun.$bulan."-".$batas;
        $return['kode'] = $kodetampil;
        $return['tahun_bulan'] = $tahun.$bulan;
        return $return;
    }
    
   
    function __construct()
    {
        parent::__construct();
        // Cek session
        $this->sesion->cek_session();

        // id dari menu Repository
        $this->id_menu = 104;

        $this->member = $this->db->select('c.id, a.id as id_profile')->join('membership b', 'b.id_profile = a.id')->join('jenis_membership c', 'c.id = b.id_jenis_membership')->get_where('profile a', ['a.id_user' => $this->session->userdata('data')['id'], 'b.status' => 1]);
        $cek_member = $this->member->num_rows();
        if($cek_member > 0){
            $member = $this->member->row_array();
            $this->member = $member['id'];
            $this->id_profile = $member['id_profile'];
        }else{
            $this->id_profile = '';
            $this->member = '';
        }

        $this->load->model("laporan/memberModel", 'model');
        $this->default_template = 'templates/dashboard';
        $this->load->library('plugin');
        $this->load->helper('url');
    }
}
