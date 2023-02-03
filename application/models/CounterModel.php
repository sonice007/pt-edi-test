<?php
defined('BASEPATH') or exit('No direct script access allowed');

class CounterModel extends Render_Model
{
    // ambil data
    public function get(): object
    {
        $db = $this->db->where('date', $this->date())->get('visitor_counter')->row();
        if ($db) {
            return $db;
        } else {
            $date = [
                'date' => $this->date(),
                'counter' => 0,
            ];
            $this->db->insert('visitor_counter', $date);
            return (object)$date;
        }
    }

    // tambah pengunjung
    public function add()
    {

        $this->db->trans_start();
        // get
        $count = $this->get();

        // update
        $this->db->where('date', $count->date)
            ->update(
                'visitor_counter',
                ['counter' => (((int)$count->counter) + 1)]
            );

        $this->db->trans_complete();

        // return 
        return $this->get();
    }

    // total data
    public function getAll(): int
    {
        return (int)$this->db->select('sum(counter) as total')
            ->from('visitor_counter')
            ->get()->row()->total;

        // return ex:20
    }

    public function date()
    {
        return date('Y-m-d');
    }


    // filter data 
    // from & to 2022-01-25
    public function getByDateRange($from, $to)
    {
        return  $this->db
            ->where("date >= '$from'")
            ->where("date <= '$to'")
            ->order_by('date')
            ->get('visitor_counter')
            ->result();
    }

    // from & to 2022-01
    public function getByMonthRange($from, $to)
    {
        // convert date to year
        if (strlen($from) > 7) {
            $from = date('Y-m-01', strtotime($from));
        } else {
            $from = $from . '-01';
        }

        $date = new DateTime("last day of $to");
        $to = $date->format('Y-m-d');

        $result =  $this->db->select('MONTHNAME(date) as month, YEAR(date) as year, SUM(counter) as counter')
            ->from('visitor_counter')
            ->where("date >= '$from'")
            ->where("date <= '$to'")
            ->group_by('concat(MONTHNAME(date), YEAR(date))')
            ->order_by('date')
            ->get()
            ->result();
        return $result;
    }

    // from & to 2022
    public function getByYearRange($from, $to)
    {
        // convert date to year
        if (strlen($from) > 4) {
            $from = date('Y-01-01', strtotime($from));
        } else {
            $from = $from . '-01-01';
        }

        if (strlen($to) > 4) {
            $to = date('Y', strtotime($to));
            $to = date('Y-m-d', strtotime(((int)($to) + 1) . "-01-01 - 1 days"));
        } else {
            $to = date('Y-m-d', strtotime(((int)($to) + 1) . "-01-01 - 1 days"));
        }

        return $this->db->select('YEAR(date) as year, SUM(counter) as counter')
            ->from('visitor_counter')
            ->where("date >= '$from'")
            ->where("date <= '$to'")
            ->group_by('YEAR(date)')
            ->get()
            ->result();
    }
}
