<template>
<div>
  <div class="container-fluid">


  <div class="panel panel-default">
    <div class="panel-body p-2">
      <div class="row">
      <div class="col-6">
        <span style="font-size: 24px;">Api</span>
      </div>
      <div class="col-6">
        <i class="fa fa-kiwi-bird btn-action m-2 float-right blue" title="Dont touch me !" @click="reloadRoutes"></i>
      </div>
      </div>
    </div>
  </div>

    <table class="table" style="font-size: 16px;" >
      <thead>
      <tr>
        <th><label for="search_method">M</label>
          <select id="search_method" class="form-control" v-model="apiFilter.method" @change="changeFilter" >
            <option  :value="null">Please select</option>
            <option v-for="m in methods" :value="m.name">{{ m.name }}</option>
          </select>
        </th>
        <th><label for="search_url">URI</label>
          <input type="text" id="search_url" class="form-control" v-model="apiFilter.url" @change="changeFilter" >
        </th>
        <th>
          <label for="search_desc">DESC</label>
          <input type="text" id="search_desc" class="form-control" v-model="apiFilter.desc" @change="changeFilter" >
        </th>
        <th><label for="search_user">Edited</label>
          <select id="search_user" class="form-control" v-model="apiFilter.users"  @change="changeFilter">
            <option  :value="null">Please select</option>
            <option v-for="u in users" :value="u.id">{{ u.username }}</option>
          </select></th>
        <th>Updated</th>
        <th>Action</th>
      </tr>

      </thead>
      <tbody v-for="(a , idx ) in apiList.data" :key="idx"  >
         <tr :class="showBox === a.id ? 'selected_col' : '' ">
          <th @click="showInfo(a.id, a.method )">{{ a.method }}</th>
          <th @click="showInfo(a.id, a.method)">{{ a.url }}</th>
          <th @click="showInfo(a.id, a.method)">{{ a.desc }}</th>
          <th style="min-width: 50px; text-align: center" @click="showInfo(a.id, a.method )"> {{ users.find(u => u.id === a.user_id ) ? users.find(u => u.id === a.user_id ).username : "--"  }}</th>
          <th @click="showInfo(a.id, a.method)">{{ dateFormat(a.updated_at) }}</th>
          <th class="text-center"><i class="fa fa-trash" style="color: red" @click="deleteApi(a.id)" title="delete api"></i></th>
         </tr>
        <tr v-if="showBox === a.id ">
          <td colspan="7">
          <div class="container-fluid">
            <div class="row min-height-200">
              <div class="col-6">

                <ul class="nav nav-tabs">
                  <li class="nav-item">
                    <button  class="nav-link" :class="showBoxNav === 1 ? '' : 'active'" @click="showBoxNav = 1" >Request</button>
                  </li>
                  <li class="nav-item">
                    <button class="nav-link" :class="showBoxNav === 2 ? '' : 'active'" @click="showBoxNav = 2" >Response</button>
                  </li>
                </ul>
                <div v-if="showBoxNav === 1" class="row">
                  <h3>Request</h3>
                <table class="table" >
                  <thead>
                  <tr class="text-center">
                    <th>Name</th>
                    <th>Val.</th>
                    <th>Type</th>
                    <th>Req.</th>
                    <th>Desc</th>
                    <th>Act</th>
                  </tr>
                  </thead>
                  <tbody>
                  <tr v-for="(r, ids) in a.request" :key="ids">
                    <td>
                      <span v-if="editRequest === ids & showBox === a.id">
                        <input type="text" v-model="r.name" class="form-control" id="name" @change="commitRequest($event , {ids : ids, idx: idx } )" required>
                      </span>
                      <span v-else>{{ r.name}}</span>
                    </td>
                    <td>
                      <span v-if="editRequest === ids & showBox === a.id">
                        <input type="text" v-model="r.value" id="value" @change="commitRequest($event , {ids : ids, idx: idx } )" class="form-control">
                      </span>
                      <span v-else >{{ r.value}}</span>
                    </td>
                    <td>
                      <span v-if="editRequest === ids & showBox === a.id">
                        <input type="text" v-model="r.type" id="type" @change="commitRequest($event , {ids : ids, idx: idx } )" class="form-control">
                      </span>
                      <span v-else >{{ r.type }}</span>
                    </td>
                    <td>
                      <div v-if="editRequest === ids & showBox === a.id">
                        <select type="text" v-model="r.has_required" id="has_required" @change="commitRequest($event , {ids : ids, idx: idx } )" class="form-control" >
                          <option :value="true">Required</option>
                          <option :value="false">Not required</option>
                        </select>
                      </div>
                      <div v-else>
                        <i v-if="r.has_required" class="fa fa-check" ></i>
                        <i v-else class="fa fa-minus"></i>
                      </div>
                    </td>
                    <td>
                      <span v-if="editRequest === ids & showBox === a.id">
                        <input type="text" v-model="r.desc" id="desc" @change="commitRequest($event , {ids : ids, idx: idx } )" class="form-control">
                      </span>
                      <span v-else >{{ r.desc }}</span>
                    </td>
                    <td>
                        <div class="d-flex flex-row">
                            <i class="fa fa-edit p-2" title="edit" @click="editRequest = ids"></i>
                            <i class="fa fa-trash p-2" title="delete" @click="deleteRequest(r.id , { idx : idx , id_request : ids })"></i>
                        </div>
                    </td>
                  </tr>
                  </tbody>
                  <tfoot>
                    <tr >
                      <td colspan="6">
                        <i class="fa fa-plus btn-action float-right" title="Add New Request" @click="addNewRequest(idx)"></i>
                      </td>
                    </tr>
                  </tfoot>
                </table>
                </div>

                <div v-if="showBoxNav === 2" class="row">
                  <h3>Response</h3>
                  <table class="table" >
                    <thead>
                    <tr class="text-center">
                      <th>Name</th>
                      <th>Type</th>
                      <th>Desc</th>
                      <th>Example</th>
                      <th>Act</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(r, idf) in a.response" :key="idf">
                      <td>
                      <span v-if="editResponse === idf & showBox === a.id">
                        <input type="text" v-model="r.name" class="form-control" id="name" @change="commitResponse($event , {idf : idf, idx: idx } )" required>
                      </span>
                        <span v-else>{{ r.name}}</span>
                      </td>
                      <td>
                      <span v-if="editResponse === idf & showBox === a.id">
                        <input type="text" v-model="r.type" id="type" @change="commitResponse($event , {idf : idf, idx: idx } )" class="form-control">
                      </span>
                        <span v-else >{{ r.type }}</span>
                      </td>
                      <td>
                      <span v-if="editResponse === idf & showBox === a.id">
                        <input type="text" v-model="r.desc" id="desc" @change="commitResponse($event , {idf : idf, idx: idx } )" class="form-control">
                      </span>
                        <span v-else >{{ r.desc }}</span>
                      </td>
                      <td>
                      <span v-if="editResponse === idf & showBox === a.id">
                        <input type="text" v-model="r.example" id="example" @change="commitResponse($event , {idf : idf, idx: idx } )" class="form-control">
                      </span>
                        <span v-else >{{ r.example }}</span>
                      </td>
                      <td>
                        <div class="d-flex flex-row">
                          <i class="fa fa-edit p-2" title="edit" @click="editResponse = idf"></i>
                          <i class="fa fa-trash p-2" title="delete" @click="deleteResponse(r.id , { idx : idx , id_response : idf })"></i>
                        </div>
                      </td>
                    </tr>
                    </tbody>
                    <tfoot>
                    <tr >
                      <td colspan="6">
                        <i class="fa fa-plus btn-action float-right" title="Add New Request" @click="addNewResponse(idx)"></i>
                      </td>
                    </tr>
                    </tfoot>
                  </table>


                </div>
              </div>
              <div class="col-6 pt-3">
                <label for="desc">Description</label>
                <textarea type="text" cols="2" rows="2" class="form-control" v-model="a.desc" id="desc" @change="updateDesc($event , a.id)" ></textarea>

                <div class="p-3" style="position: absolute; bottom: 0; left: 10px;">
                     <i class="fa fa-rocket btn-action red p-2" @click="sendRequest(a)" title="Be careful!"></i>
                </div>
                <div class="p-3" style="position: absolute; bottom: 0; right: 10px;">
                  <button class="btn btn-secondary  p-2 " @click="cancelUpdate">Cancel</button>
                  <button class="btn btn-primary p-2 " @click="update(a)" >Update</button>
                </div>
              </div>
            </div>
          </div>
          </td>
        </tr>
      </tbody>
    </table>
  </div>


  <nav aria-label="Page navigation example">
    <NavigationArrowsCircle
      :current="apiList.current_page"
      :from="apiList.current_page * apiList.per_page - apiList.per_page+1"
      :perPage="apiList.per_page"
      :total="apiList.total"
      :key="apiList.current_page"
      @goToPage="changePaginate($event)"/>
  </nav>

</div>
</template>

<script>
import Modal from "@/components/common/Modal";
import {Api , Api_Response , Api_Request} from "assets/js/models/Api";
import NavigationArrowsCircle from "@/components/common/NavigationArrowsCircle";
export default {
  name: "api",
  components:{Modal , NavigationArrowsCircle },
  layout : "admin",
  async asyncData({store}) {
    //await store.dispatch('users/fetchUsersList');
    await store.dispatch('api/fetchApiList');
  },

  data(){
    return {
      editRequest : null,
      editResponse: null,
      showModal: false,
      showBox : null,
      showBoxNav:1, // 1 = request ,  2 = response
      methods:[{name : "GET"}, {name : "POST"}, {name : "PATCH"},{name : "DELETE"},{name : "PUT"}],
    }
  },
  computed:{
    apiList() {
      const data = JSON.parse(JSON.stringify(this.$store.state.api.apiList))
      return data
    },
    apiFilter(){
      const data = JSON.parse(JSON.stringify(this.$store.state.api.apiFilter))
      return data
    },
    users(){
      const data  = JSON.parse(JSON.stringify(this.$store.state.users.users))
      return data;
    }
  },
  methods: {
    dateFormat(date) {
      let d = new Date(date);
      return d.getDate().toString().padStart(2, 0)  + "-" + (d.getMonth()+1).toString().padStart(2, 0) + "-" + d.getFullYear();
    },

    showInfo(data, method ) {
      if (this.showBox === data) this.showBox = null;
      else {
        this.showBox = data;
        if(method === "GET") this.showBoxNav =  2;
      }
    },

    async deleteApi(id){
      await this.$store.dispatch('api/deleteApi' , id).then(
        res => {
          this.$toast[res.type](res.msg).goAway(3000)
          this.$store.dispatch('api/fetchApiList')
        }).catch( err => {this.$toast[err.type](err.msg).goAway(3000)});
    },

    async reloadRoutes(){
      await this.$store.dispatch("api/reloadRoutes" ).then(
        res => {
          this.$toast[res.type](res.msg).goAway(3000)
          this.$store.dispatch('api/fetchApiList')
        }).catch( err => {this.$toast[err.type](err.msg).goAway(3000)});
    },

    async sendRequest(data){
      switch(data.method ){
        case "GET":
          console.log(data);
          console.log(this.$axios.baseUrl);
          if(data.url.indexOf('api/{token}') >= 0 ){
            data.url = data.url.replace( 'api/{token}' , this.$axios.baseUrl );
          }else if (data.url.indexOf(process.env.serverUrl) >= 0 ) {
            data.url = process.env.serverUrl +'/' + data.url;
          }
          console.log(data.url);

          await this.$axios.$get(data.url).then( res => {console.log(res);
            let type =  typeof res;
           switch (type){
             case "array" :
               console.log("array");
               break;
             case "object":
               for (var key in res) {
                 if(key == 0){
                   this.addNewResponse(0);
                   this.$store.commit('api/SET_RESPONSE' , { idx : 0 , idf : 0 });
                   this.$store.commit('api/SET_RESPONSE' , { idx : 0 , idf : 0 , name : "name" , value :  "[]" } );
                   this.$store.commit('api/SET_RESPONSE' , { idx : 0 , idf : 0 , name : "type" , value :  "array" } );
                   this.$store.commit('api/SET_RESPONSE' , { idx : 0 , idf : 0 , name : "example" , value :  "Объект упакован в массив" } );
                     var index = 1;
                     for( var val  in res[0]){
                       this.addNewResponse(0);
                       this.$store.commit('api/SET_RESPONSE' , { idx : 0 , idf : index });
                       this.$store.commit('api/SET_RESPONSE' , { idx : 0 , idf : index , name : "name" , value :  val } );
                       this.$store.commit('api/SET_RESPONSE' , { idx : 0 , idf : index , name : "type" , value :  typeof res[0][val] } );
                       this.$store.commit('api/SET_RESPONSE' , { idx : 0 , idf : index , name : "example" , value :  res[0][val] } );
                       index +=  1;
                     }
                 }
               }
               break;
             default:
               console.log(type);
           }

          } ).catch( e => {console.log(e);});
          break;
        case "POST":
          alert("ti 4e ?");
          //   await this.$axios.$post(data.url).then(res => {console.log(res)});
          break;
        case "PUT":
          alert("ti 4e ?");
          //  await this.$axios.$put(data.url).then(res => {console.log(res)});
          break;
        case "PATCH":
          //  await this.$axios.$patch(data.url).then(res => {console.log(res)});
          alert("ti 4e ?");
          break;
        case "DELETE":
          alert("ti 4e ?");
          //    await this.$axios.$delete(data.url).then(res => {console.log(res)});
          break;
      }
    },

    async update(data) {

      await this.$store.dispatch('api/updateApi', data).then(
        res => {
          this.$toast[res.type](res.msg).goAway(3000)
          this.$store.dispatch('api/fetchApiList')
          this.editRequest = null;
          this.showBox = null;
        }
      ).catch(err => {
        this.$toast[err.type](err.msg).goAway(3000)
      })
    },

    cancelUpdate() {
      this.showBox = null;
    },

    commitFilterValues() {
      this.$store.commit('api/SET_OBJ', {name: 'apiFilter', value: this.apiFilter})
    },

    commitRequest(e , data){
      this.$store.commit('api/SET_REQUEST' , { idx : data.idx , ids : data.ids , name : e.target.id , value : e.target.value   })
    },

    commitResponse(e , data){
      this.$store.commit('api/SET_RESPONSE' , { idx : data.idx , idf : data.idf , name : e.target.id , value : e.target.value   })
    },

    updateDesc(data, el){
      this.$store.commit('api/SET_APILIST_VAL', { id : el , name: data.target.id, value: data.target.value})
    },

    async changeFilter() {
      this.commitFilterValues();
      await this.$store.dispatch('api/fetchApiList')
    },

    async changePaginate(page) {
      this.apiFilter.page = page
      this.commitFilterValues()
      await this.$store.dispatch('api/fetchApiList')
    },

    addNewRequest(idx) {
      let has_empty = this.$store.state.api.apiList.data[idx].request.find( i => i.name === '');

      if(!Boolean(has_empty)) {
        this.$store.commit('api/ADD_REQUEST', idx);
        this.editRequest = this.$store.state.api.apiList.data[idx].request.length - 1;
      }
    },

    addNewResponse(idx){
      let has_empty = this.$store.state.api.apiList.data[idx].response.find( i => i.name === '');

      if(!Boolean(has_empty)) {
        this.$store.commit('api/ADD_RESPONSE', idx);
        this.editResponse = this.$store.state.api.apiList.data[idx].response.length - 1;
      }
    },


    async deleteResponse(id , data =[]){ // idx id_response
      if(id){
        await this.$store.dispatch("api/deleteResponse", id).then(
          res => {
            this.$toast[res.type](res.msg).goAway(3000);
            this.$store.dispatch('api/fetchApiList')
          }
        ).catch(err => {
          console.log(err);
        })
      }else if(data.id_request){
        this.$store.commit('api/SPLICE_RESPONSE', data);
      }
    },

    async deleteRequest(id, data = {} ) { //idx id_request
      if (id) {
        await this.$store.dispatch("api/deleteRequest", id).then(
          res => {
            this.$toast[res.type](res.msg).goAway(3000);
            this.$store.dispatch('api/fetchApiList')
          }
        ).catch(err => {
          console.log(err);
        })
      }else if(data.id_request){
        this.$store.commit('api/SPLICE_REQUEST', data);
      }
    }
  },

}
</script>

<style scoped>
.blue{
  color:blue;
}
.selected_col{
  background-color: lightskyblue;
}
.min-height-200{
  min-height: 200px;
}

table{
  font-weight:normal;
}

td {
  font-weight:normal;
  font-size: 16px;
  padding: 5px;
}


th {
  font-weight:normal;
  font-size: 16px;
  padding: 5px;
}


</style>
