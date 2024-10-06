<template>
  <div ref="prew">
    <div class="col-12">
      <div class="row justify-content-around">
        <div class="pt-2">
          <select id="locale" class="form-control" v-model="eventBill.locale" @change="updateBill($event)">
            <option v-for="lang in langsList" :key="lang.id" :value="lang.id">{{ lang.name }}</option>
          </select>
        </div>
        <div class="pt-2">
          <select id="template_number" class="form-control" v-model="templateNumber">
            <option v-for="templ in templateList" :key="templ.id" :value="templ.name">{{ templ.alias }}</option>
          </select>
        </div>
        <div v-if="eventBill.clients&&isElementInteractible('download-bill', eventBill.act_user_id)" class="nav-item m-2">
            <button class="btn btn-outline-success" ref="download"
                    @click="isIncludedInPackage('gold') ?
                      generatePDFLocallyTextMode(eventBill.firms.id, eventBill.invoice, true) :
                      generatePDFLocallyPictureMode(eventBill.firms.id, eventBill.invoice)">
              {{ $t("bills.download")}}
            </button>
        </div>
        <div class="break"></div>
        <div v-if="eventBill.clients" class="m-2">
          <input type="email"
                 id="sender_mail"
                 :placeholder="$t('bills.email')"
                 v-model="eventBill.sender_mail"
                 @change="updateBill($event)"/>
        </div>
        <div v-if="eventBill.clients" class="m-2">
          <button class="btn btn-outline-info"
                  @click="showEmailSendModal = !showEmailSendModal"
                  :disabled="!isElementInteractible('send-bill', eventBill.act_user_id)">
            {{ $t("bills.send_email")}}
          </button>
        </div>
        <div class="float-right m-2">
          <button class="btn btn-outline-info" v-if="eventBill.type === true"
                  @click="loadToEdit(eventBill.id)"
                  :disabled="!isElementInteractible('edit-bill-details', eventBill.act_user_id)">
            {{$t("bills.edit")}}
          </button>
        </div>
      </div>
      <client-only>
        <vue-html2pdf ref="html2Pdf"
                      :show-layout="false"
                      :float-layout="true"
                      :preview-modal="false"
                      :pdf-quality="1"
                      pdf-format="a4"
                      :manual-pagination="true"
                      pdf-orientation="portrait"
                      :has-blob="htmlToPdf.hasBlob"
                      pdf-content-width="730px"
                      @beforeDownload="beforeDownload($event)"
                      :enable-download="htmlToPdf.enableDownload"
                      :html-to-pdf-options="htmlToPdf.options"
        >
          <section slot="pdf-content">
            <PreviewComponent v-if="templateNumber === 'previewComponent'" :previewData="eventBill" ></PreviewComponent>
            <LazyPreviewComponent2 v-if="templateNumber === 'previewComponent2'" :previewData="eventBill"></LazyPreviewComponent2>
            <LazyPreviewComponent3 v-if="templateNumber === 'previewComponent3'" :previewData="eventBill"></LazyPreviewComponent3>
            <LazyPreviewComponent4 v-if="templateNumber === 'previewComponent4'" :previewData="eventBill"></LazyPreviewComponent4>
            <LazyPreviewComponent5 v-if="templateNumber === 'previewComponent5'" :previewData="eventBill"></LazyPreviewComponent5>
            <LazyPreviewComponent6 v-if="templateNumber === 'previewComponent6'" :previewData="eventBill"></LazyPreviewComponent6>
            <LazyPreviewComponent7 v-if="templateNumber === 'previewComponent7'" :previewData="eventBill"></LazyPreviewComponent7>
            <LazyPreviewComponent8 v-if="templateNumber === 'previewComponent8'" :previewData="eventBill"></LazyPreviewComponent8>
            <LazyPreviewComponent9 v-if="templateNumber === 'previewComponent9'" :previewData="eventBill"></LazyPreviewComponent9>
            <LazyPreviewComponent10 v-if="templateNumber === 'previewComponent10'" :previewData="eventBill"></LazyPreviewComponent10>
            <span class="html2pdf__page-break"></span>
          </section>
        </vue-html2pdf>
      </client-only>
      <div data-spy="scroll" data-offset="0" :class="[{'previewComponentWrapper': templateNumber != 'previewComponent3'}, 'normal']">
        <PreviewComponent ref="previewComponent" v-if="templateNumber === 'previewComponent'" :previewData="eventBill" @changeFirm="(data) => { $emit('changeFirm' , data )}"
                          @changeClient="(data) => { $emit('changeClient' , data )}"
                          @changeDetails="(data) => { $emit('changeDisplay' , data )}"
                          :key="pD_key"></PreviewComponent>
        <LazyPreviewComponent2 ref="previewComponent2" v-if="templateNumber === 'previewComponent2'" :previewData="eventBill" :key="pD_key"></LazyPreviewComponent2>
        <LazyPreviewComponent3 ref="previewComponent3" class="p-3" v-if="templateNumber === 'previewComponent3'" :previewData="eventBill" :key="pD_key"></LazyPreviewComponent3>
        <LazyPreviewComponent4 ref="previewComponent4" v-if="templateNumber === 'previewComponent4'"    :previewData="eventBill" 
                          @changeFirm="(data) => { $emit('changeFirm' , data )}"
                          @changeClient="(data) => { $emit('changeClient' , data )}"
                          @changeDetails="(data) => { $emit('changeDisplay' , data )}"
                          :key="pD_key"></LazyPreviewComponent4>
        <LazyPreviewComponent5 ref="previewComponent5" v-if="templateNumber === 'previewComponent5'" :previewData="eventBill" 
                          @changeFirm="(data) => { $emit('changeFirm' , data )}"
                          @changeClient="(data) => { $emit('changeClient' , data )}"
                          @changeDetails="(data) => { $emit('changeDisplay' , data )}"
                          :key="pD_key"></LazyPreviewComponent5>
        <LazyPreviewComponent6 ref="previewComponent6" v-if="templateNumber === 'previewComponent6'" :previewData="eventBill" 
                          @changeFirm="(data) => { $emit('changeFirm' , data )}"
                          @changeClient="(data) => { $emit('changeClient' , data )}"
                          @changeDetails="(data) => { $emit('changeDisplay' , data )}"
                          :key="pD_key"></LazyPreviewComponent6>
        <LazyPreviewComponent7 ref="previewComponent7" v-if="templateNumber === 'previewComponent7'" :previewData="eventBill" 
                          @changeFirm="(data) => { $emit('changeFirm' , data )}"
                          @changeClient="(data) => { $emit('changeClient' , data )}"
                          @changeDetails="(data) => { $emit('changeDisplay' , data )}"
                          :key="pD_key"></LazyPreviewComponent7>
        <LazyPreviewComponent8 ref="previewComponent8" v-if="templateNumber === 'previewComponent8'" :previewData="eventBill" 
                          @changeFirm="(data) => { $emit('changeFirm' , data )}"
                          @changeClient="(data) => { $emit('changeClient' , data )}"
                          @changeDetails="(data) => { $emit('changeDisplay' , data )}"
                          :key="pD_key"></LazyPreviewComponent8>
        <LazyPreviewComponent9 ref="previewComponent9" v-if="templateNumber === 'previewComponent9'" :previewData="eventBill" 
                          @changeFirm="(data) => { $emit('changeFirm' , data )}"
                          @changeClient="(data) => { $emit('changeClient' , data )}"
                          @changeDetails="(data) => { $emit('changeDisplay' , data )}"
                          :key="pD_key"></LazyPreviewComponent9>
        <LazyPreviewComponent10 ref="previewComponent10" v-if="templateNumber === 'previewComponent10'" :previewData="eventBill" 
                          @changeFirm="(data) => { $emit('changeFirm' , data )}"
                          @changeClient="(data) => { $emit('changeClient' , data )}"
                          @changeDetails="(data) => { $emit('changeDisplay' , data )}"
                          :key="pD_key"></LazyPreviewComponent10>
      </div>
      <SendEmailComponent :data='eventBill'
                          :is-active="showEmailSendModal"
                          @setInActive="showEmailSendModal = false"
                          @updateBill="updateBill"
                          @generatePdf="isIncludedInPackage('gold') ?
                            generatePDFLocallyTextMode($event.firm_id, $event.invoice, $event.has_filename) :
                            generatePDFLocallyPictureMode($event.firm_id, $event.invoice, $event.has_filename)"
                          @sendMail="sendMail($event.firm_id, $event.invoice, $event.mail_attachment)">
      </SendEmailComponent>
      <ChooseBankComponent v-if="showModal" :active="showModal"></ChooseBankComponent>
    </div>
  </div>
</template>

<script>
import SendEmailComponent from "@/components/bills/generator/SendEmailComponent";
import PreviewComponent from "@/components/bills/previewComponent";
import isElementInteractible from "../../../mixins/isElementInteractible";
import isIncludedInPackage from "../../../mixins/isIncludedInPackage";
import { jsPDF } from "jspdf";
import { AstraSans }  from "../../../assets/js/font/AstraSansfont";
import { Montserrat }  from "../../../assets/js/font/Montserratfont";
/*import { DejaVu }  from "../../../assets/js/font/DejaVufont";*/

export default {
  name: "Page4Component",
  components: {
    PreviewComponent,
    SendEmailComponent,
    LazyPreviewComponent2: () => import('@/components/bills/previewComponent2'),
    LazyPreviewComponent3: () => import('@/components/bills/previewComponent3'),
    LazyPreviewComponent4: () => import('@/components/bills/previewComponent4'),
    LazyPreviewComponent5: () => import('@/components/bills/previewComponent5'),
    LazyPreviewComponent6: () => import('@/components/bills/previewComponent6'),
    LazyPreviewComponent7: () => import('@/components/bills/previewComponent7'),
    LazyPreviewComponent8: () => import('@/components/bills/previewComponent8'),
    LazyPreviewComponent9: () => import('@/components/bills/previewComponent9'),
    LazyPreviewComponent10: () => import('@/components/bills/previewComponent10'),
    ChooseBankComponent: () => import('@/components/bills/generator/ChooseBankComponent')
  },
  mixins: [ isElementInteractible, isIncludedInPackage ],
  props: [ "eventBill" ],
  data() {
    return {
      error: { show: false },
      templateList: [
        { alias: "1. Blue Standard", name: 'previewComponent' },
        { alias: "2. Gray Standard", name: 'previewComponent2' },
        { alias: "3. Neutral", name: 'previewComponent3' },
        { alias: "4. Valentines day", name: 'previewComponent4' },
        { alias: "5. 8 March", name: 'previewComponent5' },
        { alias: "6. Sea Breeze", name: 'previewComponent6' },
        { alias: "7. Greenpeace", name: 'previewComponent7' },
        { alias: "8. Halloween", name: 'previewComponent8' },
        { alias: "9. Mеrry Christmas", name: 'previewComponent9' },
        { alias: "10. IT Day", name: 'previewComponent10' }
      ],
      templateNumber: "previewComponent",
      invoiceExportAction: null,
      showEmailSendModal: false,
      mail_attachment: null,
      htmlToPdf: {
        enableDownload: false,
        hasBlob: false,
        options: {
          margin: 7,
          filename: "default",
          html2canvas: {
            scrollX: 0,
            scrollY: 0,
            dpi: 192,
            scale: 4,
            letterRendering: true,
            useCORS: true
          }
        }
      },
      pD_key: 0
    }
  },

  computed: {
    langsList() {
      return this.$i18n.locales;
    },
    showModal(){
      return this.$store.state.bills.chooseBankModal;
    },
  },

  methods: {

    /**
     * Retrieves invoice data by given id and opens editing UI
     * @param {Number} id - id of invoice to edit
     */
    loadToEdit(id) {
      this.$store.dispatch("bills/getDataById", id);
      this.$emit("changeDisplay", 0);
    },

    /**
     * Updates bill data in store given data object
     * @param {Object} data - data object to store
     * @param {Number} data.target.id - id of object to store
     * @param {Object} data.target.value - value of object to store
     */
    updateBill(data) {
      this.eventBill[data.target.id]= data.target.value;
      this.$store.commit('bills/SET_OBJ', {name: 'eventBill', value: this.eventBill})
    },

    /**
     * Generates pdf from rendered html and saves or sends it
     * @param {Number} firm_id id of firm that sends invoice
     * @param {Number} invoice_number invoice id that is opened
     */
    async generatePDFLocallyTextMode(firm_id, invoice_number, has_filename = true) {
      // TODO: check on backend if invoice name is used and why cannot it be sent as a parameter
      // if(has_filename)
      // else var filename = `generated`;
      var filename = `f${firm_id}_in${invoice_number}`;
      var s = new XMLSerializer();
      this.eventBill.is_pdf_pic_mode = true; // set correct width and scale for text mode
      this.pD_key += 1;
      await this.$nextTick();
      var d = this.$refs[this.templateNumber].$el;
      this.width = this.$refs[this.templateNumber].$el.clientWidth;
      var element_html_str = s.serializeToString(d);
      this.eventBill.is_pdf_pic_mode = false; // revert UI back from text mode to pic mode
      this.pD_key += 1;
      var doc = new jsPDF('p','px','a4');

      doc.addFileToVFS('Astra-Sans.ttf', AstraSans);
      doc.addFont('Astra-Sans.ttf', 'Astra', 'normal');

      //doc.addFileToVFS('Montserrat.ttf', Montserrat);
      //doc.addFont('Montserrat.ttf', 'Montserrat', 'normal');

      // src: https://stackoverflow.com/a/54847643
      let pWidth = doc.internal.pageSize.width; // 595.28 is the width of a4
      let srcWidth = d.scrollWidth / 1.45 // 1 
      let margin = 28; // narrow margin - 1.27 cm (36); // 18
      //let scale = ((pWidth - margin * 2)/ srcWidth); // 2
      let k = '';
      if (this.width < 991){
        console.log('ok');
        this.k = 6;
       }else{
          this.k = 1;
       }
       let scale = ((pWidth - margin * this.k)/ srcWidth);
      let self = this;
      doc.html(element_html_str, {
        callback: function (doc) {
          self.addTextToPdf(doc, filename);
          if (self.invoiceExportAction == 'mail') {
            self.invoiceExportAction = null;
            let res = doc.output('datauristring', `${filename}.pdf`);
            var fd = new FormData();
            fd.append('sender_mail', self.eventBill.sender_mail);
            fd.append('attachment['+ self.mail_attachment.attachment.length +'][name]', `${filename}.pdf`);
            fd.append('attachment['+ self.mail_attachment.attachment.length +'][data]', res);
            fd.append('attachment['+ self.mail_attachment.attachment.length +'][type]', 'application/pdf');
            if (self.mail_attachment.text) {
              fd.append('text', self.mail_attachment.text);
            }
            if (self.mail_attachment.attachment.length > 0) {
              for(var i = 0 ; i < self.mail_attachment.attachment.length; i++){
                fd.append('attachment['+ i +'][name]', self.mail_attachment.attachment[i]['name']);
                fd.append('attachment['+ i +'][data]', self.mail_attachment.attachment[i]['data']);
                fd.append('attachment['+ i +'][type]', self.mail_attachment.attachment[i]['type']);
              }
            }
            self.$store.dispatch("bills/sendMail", fd);
          } else {
            doc.output('save', `${filename}.pdf`);
            return;
          }
        },
        x: 20, //10
        y: 10, //10
        html2canvas: {
          scale: scale,
        },
      });
    },

    /**
     * Relays command to start generating pdf
     * @param {Number} firm_id id of firm that sends invoice
     * @param {Number} invoice_number invoice id that is opened
     */
    generatePDFLocallyPictureMode(firm_id, invoice_number){
      this.invoiceExportAction = 'pdf';
      this.htmlToPdf.enableDownload = false;
      this.htmlToPdf.options.filename = `f${firm_id}_in${invoice_number}`;
      this.$refs.html2Pdf.generatePdf();
    },

    /**
     * Relays data to generate pdf for sending emails
     * @param {Number} firm_id id of firm that sends invoice
     * @param {Number} invoice_number invoice id that is opened
     */
    async sendMail(firm_id, invoice_number, attachment = null) {
      this.mail_attachment = attachment;
      if (this.eventBill.sender_mail !== null) {
          this.error.show = false;
          this.error.msg = "";
          this.invoiceExportAction = 'mail';
          if (this.isIncludedInPackage('gold')) {
            this.generatePDFLocallyTextMode(firm_id, invoice_number, false);
          } else {
          this.htmlToPdf.options.filename = `f${firm_id}_in${invoice_number}`;
          this.htmlToPdf.enableDownload = false;
          var pdfContent = this.$refs.html2Pdf;
          await pdfContent.generatePdf();
        }
      } else {
        this.error.show = true;
        this.error.msg = "Please enter email";
      }
    },

    /**
     * Constructs pdf for emailing and saving from exposed object
     * Gets triggered when pdf file content is generated
     */
    async beforeDownload({ html2pdf, options, pdfContent }){
      if (this.invoiceExportAction == 'pdf') {
        const res = await html2pdf().set(options).from(pdfContent).toPdf().get('pdf').then((pdf) => {
          this.addTextToPdf(pdf);
        }).save();
      } else if (this.invoiceExportAction == 'mail') {
        const res = await html2pdf().set(options).from(pdfContent).toPdf().get('pdf').then((pdf) => {
          this.addTextToPdf(pdf);
        }).output('datauristring');

        if(this.eventBill.sender_mail != null) {
          var fd = new FormData();
          fd.append('sender_mail', this.eventBill.sender_mail);
          fd.append('data', res);
          this.$store.dispatch("bills/sendMail", fd);
        }
        return;
      }
    },

    /**
     * Adds custom text to generated pdf
     */
    addTextToPdf(pdf, filename){
      const totalPages = pdf.internal.getNumberOfPages()
      let pdfPageHeight = pdf.internal.pageSize.getHeight();
      let pdfPageWidth = pdf.internal.pageSize.getWidth();
      for (let i = 1; i <= totalPages; i++) {
          pdf.setPage(i)
          pdf.setFontSize(10)
          pdf.setTextColor(0)
          if (totalPages != 1) {
            pdf.text(''+filename, (pdfPageWidth*0.88), 6);
            pdf.text(i + '/' + totalPages, (pdfPageWidth*0.9), (pdfPageHeight - 5));
          }
      }
    }
  }
}
</script>

<style lang="scss" scoped>
@import "./assets/scss/_var.scss";
  #sender_mail{
      border-radius: 5px;
      height: 35px;
  }

  @media (max-width: 390px){
    .row{
      display: flex;
      align-items: column;
      justify-content: center;
    }
    #template_number{
      margin-left: -6px;
    }
  }

  #sender_mail{
      border-radius: 5px;
      height: 35px;
  }

  @media (max-width: 1200px){
    .break {
      flex-basis: 100%;
      height: 0;
    }
  }
  .previewComponentWrapper {
    border: grey dashed 1px;
  }  
    @media (max-width: 991.5px) {
  .previewComponentWrapper {
      transform: scale(0.75);
      margin-top: -30%;
      margin-left: -15%;
      border: none;
       }
    }
    @media (max-width: 980px) {
  .previewComponentWrapper {
      transform: scale(0.70);
       }
    }
    @media (max-width: 767px) {
   .previewComponentWrapper {
      transform: scale(0.9);
      margin-top: -10%;
      margin-left: -10%;
       }
    }
    @media (max-width: 749px) {
   .previewComponentWrapper {
      transform: scale(1);
      margin-top: 0%;
      border: none;
       }
    }
    @media (max-width: 575px) {
   .previewComponentWrapper {
      transform: scale(0.75);
      margin-top: -15%;
       }
    }
    @media (max-width: 499px) {
    .previewComponentWrapper {
      transform: scale(0.6);
      margin-top: -50%;
      margin-left: -20%;
       }
    } 
    @media (max-width: 450px) {
     .previewComponentWrapper {
      transform: scale(0.55);
       }
    }
    @media (max-width: 399px) {
   .previewComponentWrapper {
      transform: scale(0.5);
      margin-top: -60%;
      margin-left: -30%;
       }
    }
    @media (max-width: 350px) {
    .previewComponentWrapper {
      transform: scale(0.4);
    }
  }



</style>

