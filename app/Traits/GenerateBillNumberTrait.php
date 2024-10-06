<?php


namespace App\Traits;

trait GenerateBillNumberTrait
{

    /** get Collection with numbers - return uniq or last number for creating invoice
     * @param $numbers
     * @return array|int[]
     */

    public function getLastNumber($numbers): array{
        $count = $numbers->count();
        $res = ["number" => 1];
        if($count != 0){
            $el = $numbers->pop();
            if ($el > $count) {
                for ($i = 1; $i < $el; $i++) {
                    if (!$numbers->contains($i)) {
                        return ["number" => $i];
                    }
                }
            } else $res = ["number" => ++$el];
        }
        return $res;
    }

    /** That method for getting new invoice
     * @param int $month
     * @param int $year
     * @param int $number
     * @return string
     */

    public function getInvoiceStr(int $month , int $year ,  int $number ) : string{
        return  str_pad($month ,  2, '0', STR_PAD_LEFT) . substr($year , -2 , 2)  . str_pad($number, 3, '0', STR_PAD_LEFT);
    }

}
