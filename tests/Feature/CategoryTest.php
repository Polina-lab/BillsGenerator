<?php

namespace Tests\Feature;

use App\Helpers\Facade\Switcher;
use App\Models\Bills\Firms;
use App\Models\Users\User;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;
use App\Models\Bills\Categories;


class CategoryTest extends TestCase
{
    use WithFaker;
    public $team;
    public $user;
    public $mock;


    public function setUp() :void
    {
        parent::setUp();
        $this->user =    User::where('email', 'rowlinest90@gmail.com')->first();
        //TODO: need to mock user
        $this->team  = $this->user->team()->first();

        Sanctum::actingAs(
            $this->user ,
            ['*']
        );
    }


    public function testCategoriesGetByFirmId($id = null)
    {
        if ($id  == null) {
            $firm = $this->getFirstFirm();
            $id  = $firm->id;
        }else{
            Switcher::useDefaultDB();
        }

        $resp = $this->get('/api/' . $this->team->key . '/categories/1' );

        $resp->assertStatus(200);
        return $resp;
    }

    protected function getTestDataForMoving($data){
        return [
            "element" => $data['element'],
            "oldIndex" => 7,
            "newIndex" => $data['newIndex'],
            "status" => $data['action'],
            "parent" => $data['parent'],
        ];
    }



    public function testMovingAddedCategory(){
        $data['element'] = $this->getFirstCategoryNoNullPath();
        $data['action'] = 'added';
        $data['parent'] = null;
        $data['newIndex'] = rand(1, 10);
        $resp = $this->post('/api/' . $this->team->key . '/categories/rebuild' , $this->getTestDataForMoving($data) );
        $resp->assertStatus(200);
        $this->assertDatabaseHas('categories' , ['id' => $data['element']['id'] , 'parent_id' => $data['parent'] , 'order' => $data['newIndex'] ]);
    }

    public function testMovingMovedCategoryInThisPath(){
        $data['element'] = $this->getFirstCategory();
        $data['action'] = 'moved';
        $data['parent'] = 1;
        $data['newIndex'] = rand(1, 10);
        $resp = $this->post('/api/' . $this->team->key . '/categories/rebuild' , $this->getTestDataForMoving($data) );
        $resp->assertStatus(200);
        $this->assertDatabaseHas('categories' , ['id' => $data['element']['id'] , 'parent_id' => $data['parent'] , 'order' => $data['newIndex'] ]);
    }



    protected function getTreeTestData( $firm_id){
        return '{"data":{"id":null,"parent_id":null,"name":"sss","children":[],"desc":"","firms_id":"'.$firm_id.'"},"firms_id":"1"}';
    }

    /**
     * @param \stdClass $data
     * @return string
     */
    public function createChild( \stdClass $data){
        return '[{"id":'.$data->id. ',"parent_id":null,"name": '.$data->name.',"children":[{"id":null,"parent_id":'. $data->id.',"name":"lMbFzC9c","children":[],"desc":"1eTO6RUFs1w5bpEO","firms_id":'.$data->firms_id.',"_lft":null,"_rgt":null}],"desc":'.$data->desc.',"firms_id":'. $data->firms_id.',"_lft":null,"_rgt":null}]';
    }



    protected function getTreeTestDataUpdated($firm_id){
        return '{"id":null,"parent_id":null,"name":"XrDheL1I","children":[{"id":null,"parent_id":null,"name":"lMbFzC9c","children":[],"desc":"1eTO6RUFs1w5bpEO","firms_id":'.$firm_id.',"_lft":null,"_rgt":null},{"id":null,"parent_id":null,"name":"tjlsICeQ","children":[{"id":null,"parent_id":null,"name":"i3ouwjzU","children":[],"desc":"WQ95F9uvMEfhUCeh","firms_id":'.$firm_id.',"_lft":null,"_rgt":null}],"desc":"SiEITIZameanjoni","firms_id":'. $firm_id.',"_lft":null,"_rgt":null}],"desc":"yyTfpn9VjoqSuFeA","firms_id":'.$firm_id.',"_lft":null,"_rgt":null}';
    }



    protected function getData($data){
        return [
            'name'=> $data['name']  ?? $this->faker->name,
            'firms_id' => 1
        ];
    }

    protected function getFirstCategory(){
        Switcher::useLocalDB($this->team->database);
        $res = Categories::all()->first();
        Switcher::useDefaultDB();
        return $res;
    }

    protected function getFirstCategoryNoNullPath(){
        Switcher::useLocalDB($this->team->database);
        $res = Categories::where('parent_id' , "!=" , null)->first();
        Switcher::useDefaultDB();
        return $res;
    }


    protected function getFirstFirm(){
        Switcher::useLocalDB($this->team->database);
        $res = Firms::all()->last();
        Switcher::useDefaultDB();
        return $res;
    }

    public function testCategoriesCreate(){
        $response = $this->post('/api/' . $this->team->key . '/categories/create' , json_decode('{"data":{"id":null,"parent_id":"33","name":"sss","children":[],"desc":"","firms_id":"1"},"firms_id":"1"}' , true));
        $response->assertStatus(200);
    }
/*
    public function testCategoriesTreeCreate(){
        $firm = $this->getFirstFirm();
        $response  = $this->post('/api/' . $this->team->key . '/categories/create/', [ 'firms_id' =>  $firm->id , "data" =>  $this->getTreeTestData($firm->id)] );
        $response->assertStatus(200);
        $resp =  $this->testCategoriesGetByFirmId($firm->id);
        $resp->assertStatus(200);
        $data  = $resp->getData();
    }

    public function testCategoriesTreeUpdate(){
        $firm = $this->getFirstFirm();
        $response  = $this->post('/api/' . $this->team->key . '/categories/update/', [ 'firms_id' =>  $firm->id , "data" =>  $this->getTreeTestDataUpdated()] );
        $response->assertStatus(200);
    }
*/

    public function testCategoriesUpdate()
    {
        $new_name  = $this->faker->name;
        $categories =  $this->getFirstCategory();
        $response = $this->post('/api/' . $this->team->key . "/categories/$categories->id" , $this->getData(['name' => $new_name]));
        $response->assertStatus(200);
        $this->assertDatabaseHas('categories' ,['id' => $categories->id  , 'name' => $new_name]);
    }


    public function testCategoriesDelete()
    {
        $categories =  $this->getFirstCategory();
        $response = $this->delete('/api/' . $this->team->key . "/categories/$categories->id");
        $response->assertStatus(200);
        $this->assertDatabaseMissing('categories' , ['id' => $categories->id]);
    }

}
