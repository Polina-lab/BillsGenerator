<?php
namespace Tests\Feature\Bills;


use App\Helpers\Facade\Switcher;
use App\Models\Bills\BankDetails;
use App\Models\Bills\Firms;
use App\Models\Bills\FirmsVat;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;

trait FirmTrait{

    use WithFaker;

    protected function getNewVat($data  =  []):array{
        return [
            'vat' => $data['vat'] ?? $this->faker->rand(0, 20),
            'default' => $data['default'] ?? rand(0,1)
        ];
    }


    /**
     *

     * @param array $data
     * @return array
     */


    protected function getNewBank($data =[]): array{
        return [
                "bank_name" => $data['bank_name'] ?? $this->faker->company,
                "bank_swift" => $data['bank_swift'] ?? $this->faker->swiftBicNumber,
                "bank_address" => $data['bank_address'] ?? $this->faker->address,
                "bank_num" => $data['bank_num'] ?? $this->faker->bankAccountNumber,
            ];
    }

    /**
     *
    "name"=> "required",
    "company_name" => "nullable",
    "reg_num"=> "required",
    "vat_reg_num" => "nullable"
    "status"=> "nullable",
    "phone"=>"nullable" ,
    "address"=> "nullable|max:200",
    "main_firm" => 'nullable|boolean',
     *
     * @param array $data
     * @return array
     *
     */
    protected function getNewFirmData($data = []): array{
      $res =  [
          "name" => $data['name'] ?? $this->faker->company,
          "reg_num" => $data["reg_num"] ?? Str::random(13),
          'vat_reg_num' => $data['vat_reg_num'] ?? Str::random(15),
          "title" =>  $data['title'] ?? $this->faker->company,
          "company_name" => $data['company_name'] ?? $this->faker->company,
          "address" => $data['address'] ?? $this->faker->address,
          "status" =>  $data['status'] ?? rand(0, 1),
          "phone" => $data['phone'] ?? $this->faker->phoneNumber,
          "email" => $data['email'] ?? $this->faker->companyEmail,
          "view_in_invoice" => $data['view_in_invoice'] ?? rand(0, 1),
          "banks" => [$data['bank'] ?? $this->getNewBank()],
          "representatives" => $data['representatives'] ?? rand(1, 4),
          "logo" => $data['logo'] ?? null,
          "main_firm" => rand(0, 1),
          "is_footer_visible" => rand(0,1),
          "footer" => $data['footer'] ?? null,
      ];

      if(isset($data['vat'])){
          $res['vat'] = [$data['vat'] ?? $this->getNewVat()];
      }

      if(isset($data['representative_name'])){
          unset($res['representatives']);
          $res['representative_name'] = $data['representative_name'];
      }
      return $res;
    }

    /**
     * @return Firms[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getFirmsFromLocalDatabase($database){
        Switcher::useLocalDB($database);
        $firms = Firms::all();
        Switcher::useDefaultDB();
        return $firms;
    }

    /** check firms
     * @param string $database
     * @param array $data
     * @return mixed
     */

    public function checkFirmInDatabase(string $database ,array $data){
        Switcher::useLocalDB($database);
        $firms = Firms::where([$data])->get();
        Switcher::useDefaultDB();
        return $firms;
    }

    /** check firmsVat
     * @param string $database
     * @param array $data
     * @return mixed
     */

    public function checkVatInDatabase(string $database ,array $data){
        Switcher::useLocalDB($database);
        $firms_vat = FirmsVat::where($data)->get();
        Switcher::useDefaultDB();
        return $firms_vat;
    }

    /** check BankDetails
     * @param string $database
     * @param array $data
     * @return mixed
     */

    public function checkBankDetailsInDatabase(string $database ,array $data){
        Switcher::useLocalDB($database);
        $bank_details = BankDetails::where($data)->get();
        Switcher::useDefaultDB();
        return $bank_details;
    }

    /** get last firm
     * @param $database
     * @return mixed
     */

    public function getLastFirm($database){
        Switcher::useLocalDB($database);
        $firms = Firms::with('banks' , 'vat')->get()->last();
        Switcher::useDefaultDB();
        return $firms;
    }

    public function getLastFirmWith($database , array $select , array $extra = []){
        Switcher::useLocalDB($database);
        $firms = Firms::with('banks' , 'vat')
            ->when(!empty($select) , function($q ) use ($extra , $select) {
                $q->where($select)
                    ->when(isset($extra[0]) && $extra[0][0] === 'vat', function ($table) use ($extra) {
                        return $table->whereHas(
                            'vat', function ($q) use ($extra) {
                            return $q->where($extra);
                        });
                    });
            })->get()->last();
        Switcher::useDefaultDB();
        return $firms;
    }



}
