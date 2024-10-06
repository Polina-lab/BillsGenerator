<?php

namespace App\Http\Controllers\Admin\Bills;

use App\Http\Requests\Categories\UpdateRequest;
use App\Http\Requests\Categories\CreateRequest;
use App\Http\Requests\HasDigitIdRequest;
use App\Http\Resources\ArrayTransducer;
use App\Models\Bills\Categories;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{
    public $msg  ;

    public function index(HasDigitIdRequest $request){
       return Categories::where(['firms_id'=> $request->id , "parent_id" => null  ])->with('children')->orderBy('order' , "ASC")->get();
    }

    protected function checkOrder($data){
                $last_order = Categories::where([['firms_id', '=', $data['firms_id']],
                    ['parent_id', '=', $data['parent_id']]])->count();
                $data['order'] = $last_order ;
        return $data;
    }

    public function create(CreateRequest $data){
        $this->msg = ['code' => 200 , 'msg'=> 'Category created', 'type' => 'success' ];
        $data = $this->checkOrder($data->all()['data']);

        $res = Categories::create($data);
        $this->msg['data'] = $res->toArray();
        if(!$res){
            $this->msg = ['code' => 500 , 'msg' => 'Category not saved' , 'type' => 'error'];
        }
        return $this->msg;
    }

    public function update(HasDigitIdRequest $id , UpdateRequest $data){
        $this->msg = ['code' => 200 , 'msg'=> 'Category updated', 'type' => 'success' ];
        $category = Categories::where('id' , $id->id)->first();
        if($category){
            $this->checkOrder($data);
            $category->update($data->all());
        }else{
            $this->msg = ['code' => 500 , 'msg' => 'Category not updated' , 'type' => 'error'];
        }
        return $this->msg;
    }

    protected function toSort($elements , $newIndex){

        $el_to_change =  $elements->where('order' , $newIndex );
        if($el_to_change && $el_to_change->count() === 1){
                    $el_to_change->update(['order' => (int)$el_to_change->order + 1]);

        }else if($el_to_change && $el_to_change->count() > 1){
            foreach ($elements as $element => $index) {
                $element->update(['order' => $index ]);
            }
        }


    }



   public function rebuild(Request $data){
            $this->msg = ['code' => 200, 'msg' => 'Categories updated ', 'type' => 'success'];
            if ($data->element) {
                $element = Categories::whereId($data->element['id'])->first();
                if($data->status == 'added' ) {
                    $elements = Categories::where(['parent_id' => $data->parent, 'firms_id' => $data->element['firms_id']])->orderBy('order', "ASC")->get();
                    $this->toSort($elements, $data->newIndex);
                    $element->update(['parent_id' => $data->parent, 'order' => $data->newIndex]);
                }else if($data->status == 'moved'){
                    $elements = Categories::where(['parent_id' => $data->element["parent_id"], 'firms_id' => $data->element['firms_id']])->orderBy('order', "ASC")->get();
                    $old_el = $elements->where('order' , (int) $data->newIndex )->first();
                    $element->update(['parent_id' => $data->element["parent_id"], 'order' => (int) $data->newIndex]);
                    if($old_el) {
                        $old_el->update(['order' => (int)$data->oldIndex]);
                    }else{
                        $this->msg = ['code' => 502, 'msg' => 'Oops: Index not found ', 'type' => 'error'];
                    }

                }
                else if($data->status == 'removed'){
                    $element->update(['parent_id' => $data->parent, 'order' => $data->oldIndex  ]);
                }
            }

        return new ArrayTransducer($this->msg);
    }


    public function delete(HasDigitIdRequest $id){
        $this->msg = ['code' => 200 , 'msg'=> 'Category deleted', 'type' => 'success' ];
        $category = Categories::where('id' , $id->id)->first();

        if($category){
            if($category->childen != null){
                foreach ($category->children as $child){
                    $child->update(['parent_id' => null]);
                }
            }
            $category->delete();
        }else{
            $this->msg = ['code'  => 404 , 'msg' => "Category not found" , 'type' => 'error'];
        }
        return $this->msg;
    }

}
