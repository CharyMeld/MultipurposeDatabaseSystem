const { createApp, ref, reactive, provide, inject } = Vue;

const mainTemplate = `
   <div class='app-container'>
      <div class='mb-2' @click='goback'>
          <button class="btn btn-primary small back-btn"> < back</button>
      </div>
   <!-- the toast notification -->

      <div v-if='notification.message' class="alert position-fixed toast-alert" :class='statusClass' role="alert">
        {{notification.message}}      

      </div>
      <div v-if='loading' class='position-fixed toast-alert'>
        <img alt='loader' :src='loaderPath' >
      </div>

      <ul class="nav nav-tabs">
        <li class="nav-item" v-for='(item,index) in tabs' :key='item' @click='active=index'>
          <a class="nav-link" :class='active==index?"active":""' aria-current="page" href="#">{{item}}</a>
        </li>

      </ul>

     <div class='content'>
        <Overview v-if='active==0' :candidate='candidate' :faculty_name='faculty_name' :sub_speciality='sub_speciality' />
        <Biodata v-if='active==1' :faculty_name='faculty_name' :sub_speciality='sub_speciality' :biodata='candidate.biodata' :inst_form='candidate.forms.instForm' :med_form='candidate.forms.medForm' :post_form='candidate.forms.postForm'
          :candidate_form='candidate.forms.candidate' @init-update='submitForm' @init-delete='deleteEntity'  @setLoading='setLoading' @init-item-update='updateEntity' @notice='notify'
           />
        <Application v-if='active==2' :form='candidate.forms.app' :other_forms='candidate.forms' :applications='candidate.applications' :biodata='candidate.biodata'  @init-update='submitForm' @init-delete='deleteEntity' @setLoading='setLoading' />
        <Documents v-if='active==3' :applications='candidate.applications' :documents='candidate.document' :docs='candidate.documents' :fullname='fullname' @init-update='submitForm' :fields='docFields' @init-delete='deleteEntity' @setLoading='setLoading'/>
        <Results v-if='active==4' :results='candidate.result' @init-update='submitForm' @init-delete='deleteEntity' @setLoading='setLoading' />
     </div>
   </div>
 `;

const biodataTemplate = `
  <section>
    <div class='row'>
      <div class='col-md-5 p-2'>
        <div class='candidate-image-container d-flex justify-content-center mt-2 align-center'>
            <img class='text-center align-center'  :src="image" alt="profile picture" >
        </div>

        <div class='d-flex mt-2' :class='pic_frame_class'>
          <button v-if='!showUpload' class='btn btn-primary' @click='showUpload=true' >Change Picture</button>

          <div class='d-flex justify-content-between' v-if='showUpload'>
            <input accept='.png, .jpeg, .JPG, .jpg' type='file' ref='photo-file' class='form-control' @change='updateFile' />
            <button class='btn btn-default' @click='showUpload = false'><i class='fa fa-times'></i></button>
          </div>

        </div>

        <Institution item_ref='med' form_name='Medical school' label='Medical School Attended' :fields='med_form' :accordion_items='biodata.medical_school' :submitText='submitText' item_key='biodata:medical_school' @init-update='initAccordionSubmit' @init-delete='deleteApp' />

        <Institution item_ref='med' form_name='Insitution' label='Insitution Attended' :fields='inst_form' :accordion_items='biodata.institution' :submitText='submitText' item_key='biodata:institution' @init-update='initAccordionSubmit' @init-delete='deleteApp' />

        <Institution item_ref='med' form_name='Insitution' label='Postgraduate Training Attended' :fields='post_form' :accordion_items='biodata.post_training' :submitText='submitText' item_key='biodata:post_training' @init-update='initAccordionSubmit' @init-delete='deleteApp' />
      </div>

      <div class="col-md-7 p-2">
        <Profile :faculty_name='faculty_name' :sub_speciality='sub_speciality' :biodata='biodata' :candidate_form='candidate_form' @init-update='initBiodataUpdate'  />
      </div>
    </div>
  </section>
 `;

const overviewTemplate = `
  <div class='mt-5 candidate-overview'>
    <!-- <div class='d-flex p-2 justify-content-end'>
      <a href='#' class='btn btn-default' @click.prevent="showAlert">Print</a>

    </div> -->
    <div>
      <table class='w-100'>
        <tr>
          <td colspan='6' class='p-2 mb-3'>
            <h3 class='text-center'>Candidate Profile</h3>
          </td>
        </tr>

        <tr>
          <td rowspan='5' colspan='2' width='30%' style='padding:15px'>
            <img :src='image' class='overview-image'>

          </td>
          <th>
            <span>Surname</span>
          </th>
          <td>
            {{candidate.biodata.surname}}
          </td>
          <th>
            <span>Other Name</span>
          </th>
          <td>
            {{candidate.biodata.other_name}}
          </td>
          
        </tr>

        <tr>
          <th>
            <span>Registration Number</span>
          </th>
          <td>
            {{candidate.biodata.registration_number}}
          </td>
          <th>
            <span>Folio Number</span>
          </th>
          <td>
            {{candidate.biodata.cert_number}}
          </td>
        </tr>


        <tr>
          <th>
            <span>Gender</span>
          </th>
          <td>
            {{candidate.biodata.gender}}
          </td>
          <th>
            <span>Date of Birth</span>
          </th>
          <td>
            {{candidate.biodata.dob}}
          </td>
        </tr>

        <tr>
          <th>
            <span>Email Address</span>
          </th>
          <td>
            {{candidate.biodata.email}}
          </td>
          <th>
            <span>Phone Number</span>
          </th>
          <td>
            {{candidate.biodata.phone}}
          </td>
        </tr>

        <tr>
          <th>
            <span>Faculty</span>
          </th>
          <td>
            {{faculty_name}}
          </td>

          <th>
            <span>Sub Speciality</span>
          </th>
          <td>
            {{sub_speciality}}
          </td>
        </tr>

        <tr>
          <th>
            <span>Mode of Entry</span>
          </th>
          <td>
            {{candidate.biodata.entry_mode}}
          </td>
          <th>

            <span>Country</span>
          </th>
          <td>
            {{candidate.biodata.country}}
          </td>
          <th>

            <span>Maiden Name</span>
          </th>
          <td>
            {{candidate.biodata.maiden_name}}
          </td>
        </tr>
        <tr>

          <th>
            <span>Nationality</span>
          </th>
          <td>
            {{candidate.biodata.nationality}}
          </td>
          <th>
            <span>Change of Name</span>
          </th>
          <td>
            {{candidate.biodata.change_of_name}}
          </td>
          <th>
            <span>Full Registration Date</span>
          </th>
          <td>
            {{candidate.biodata.full_registration_date}}
          </td>
        </tr>
        <tr>
          <th>
            <span>Address</span>
          </th>
          <td colspan='5'>
            {{candidate.biodata.address}}
          </td>
        </tr>
        <tr>
          <th>
            <span>Postal Address</span>
          </th>
          <td colspan='5'>
            {{candidate.biodata.postal_address}}
          </td>
        </tr>

        <tr>
          <th>
            <span>NYSC Discharge/Exemption Date</span>
          </th>
          <td>
            {{candidate.biodata.nysc_discharge_or_exemption}}
          </td>
          <th>
            <span>Accredited Training Program</span>
          </th>
          <td>
            {{candidate.biodata.accredited_training_program}}
          </td>
          <th>
            <span>Year of fellowship</span>
          </th>
          <td>
            {{candidate.biodata.year_of_fellowship}}
          </td>
        </tr>
        <tr>
            <td colspan='6'>
              <h4 class='text-center p-3'>Applications/Exams </h4>
            </td>
        </tr>
        <tr v-for='(app,type) in appOrdered' :key='type'>
          <th>{{type}}</th>
          <td colspan='3'>{{listApplication(app.dates)}}</td>
          <th>passed</th>
          <td>{{listApplication(app.passed)}}</td>
        </tr>
        <tr v-if='candidate.biodata.medical_school.length > 0'>
            <td colspan='6'>
              <h4 class='text-center p-3'>Medical school Attended </h4>
            </td>
        </tr>
        <tr v-if='candidate.biodata.medical_school.length > 0' v-for='(sch,index) in candidate.biodata.medical_school' :key='sch.id'>
          <th>Name</th>
          <td>{{sch.name}}</td>
          <th>Start Date</th>
          <td>{{sch.start_date}}</td>
          <th>End Date</th>
          <td>{{sch.end_date}}</td>
        </tr>
        <tr  v-if='candidate.biodata.institution.length > 0' >
            <td colspan='6'>
              <h4 class='text-center p-3'>Institution Attended </h4>
            </td>
        </tr>
        <tr v-if='candidate.biodata.institution.length > 0' v-for='(sch,index) in candidate.biodata.institution' :key='sch.id'>
          <th>Name</th>
          <td >{{sch.name}} - {{sch.degree}}</td>
          <th>Start Date</th>
          <td>{{sch.start_date}}</td>
          <th>End Date</th>
          <td>{{sch.end_date}}</td>
        </tr>
        <tr v-if='candidate.biodata.post_training.length > 0'>
            <td colspan='6'>
              <h4 class='text-center p-3'>Postgraduate Training </h4>
            </td>
        </tr>
        <tr v-if='candidate.biodata.post_training.length > 0' v-for='(sch,index) in candidate.biodata.post_training' :key='sch.id'>
          <th>Name</th>
          <td>{{sch.name}}</td>
          <th>Start Date</th>
          <td>{{sch.start_date}}</td>
          <th>End Date</th>
          <td>{{sch.end_date}}</td>
        </tr>
      </table>
    </div>
  </div>
`;
const appTemplate = `
    <div class='mt-5'>
        <h3 class='mb-5 mt-4'>List of Candidate Application</h3>

        <div class='d-flex justify-content-end mb-4'>
          <span class='fa fa-plus cursor-pointer text-default' @click='showNew'> {{formText}}</span>
        </div>
        <Dialog modal_title='New Application Entry' :showFooter='false' v-if='showForm' @modal-close='clearForm'>
            <div class='card-body'>
              <AppForm :fields='examFields'  @formSubmitted='initSubmit' ref_name='application_form' :submitText='submitText'></AppForm>
            </div>

        </Dialog>
        

        <div class="accordion" v-if='applications && applications.length'>
          <div class="accordion-item" v-for='(app,index) in applications' :key='app.exam_number' @click='toggleOpen(index)'>
            <h2 class="accordion-header" >
            <div class='position-relative'>
              <div class="accordion-button justify-content-between" type="button"  aria-expanded="true" aria-controls="collapseOne">
                <div>{{app.application_type+' - '+fetchDisplay(app.exam_date,'exam_date')}}</div>

                <div class='action-item position-absolute px-2'>
                  <i @click.stop='editApp(index)' class='fa fa-edit pr-2'></i>
                  <i @click.stop='deleteApp(index)' class='fa fa-times px-1'></i>
                </div>
              </div>

            </div>

            </h2>
            <div  class="accordion-collapse" aria-labelledby="headingOne" v-if='index==currentOpened'>
              <div class="accordion-body p-4">
                <table class='table table-stripped table-bordered'>
                  <tr v-for='(item,label) in exam_items[index]' :key='label'>
                    <th>{{label}}</th>
                    <td>{{item}}</td>
                  </tr>
                </table>
              </div>
            </div>
          </div>
        </div>

        <div class='p5 text-center mt-5 mb-3' v-else>
          <h4 class='mb-2'> 
            <i class='fa fa-exclamation-triangle'></i>
          </h4>
          <h4>No application found for candidate</h4>
        </div>
    </div>  
 `;

const docItemTemplate = `
  <div class='card border'>
    <div class='card-header d-flex justify-content-between'>
      <div class='card-title '>
        <h4><i class='fa fa-file-pdf text-danger'></i> {{fl.title}}</h4>
      </div>
      <div class='d-flex justify-content-end'>
        <a target='__blank' class='btn btn-link' :href='path' :download='fl.title'>Download</a>
        <button class='btn btn-primary pr-3'@click.stop='showFile=!showFile'>{{viewText}}</button>

        <button class='btn btn-link text-danger pl-3'@click.stop='deleteFile'>delete</button>

      </div>
    </div>
    <div class='card-body' v-if='showFile'>
      
      <iframe  class='w-100'
        id="inlineFrameExample"
        title="Inline Frame Example"
        height="700"
        :src="path">
      </iframe> 
    </div>
  </div>
`;

overviewFieldTemplate = `
  <template>
    <th>{{fieldname}}</th>
    <td>{{fieldValue}}</td>

  </template>
`;

const OverviewField = {
	template: overviewFieldTemplate,
	props: ["fieldname", "fieldValue"],
};

const DocItem = {
	template: docItemTemplate,
	setup() {
		const config = inject("config");
		return {
			config,
		};
	},
	props: {
		fl: Object,
		nameString: Array,
		position: String,
	},
	data() {
		return {
			showFile: false,
		};
	},
	computed: {
		path() {
			return this.config.baseurl + this.fl.location;
		},
		viewText() {
			return this.showFile ? "Hide" : "Show";
		},
	},
	methods: {
		deleteFile() {
			this.$emit("init-delete", "documents", this.position);
		},
	},
};
const documentTemplate = `
    <div>
    <h3 class='text-center mb-2 mt-3'>Candidate Documents</h3>
    <div class='d-flex justify-content-end mb-4'>
      <span class='fa fa-plus cursor-pointer text-default' @click='showNew'> {{formText}}</span>
    </div>
    <Dialog modal_title='Upload New Document' :showFooter='false' v-if='showForm' @modal-close='showForm=false'>
        <div class='card-body'>
          <AppForm :fields='docFields'  @formSubmitted='initSubmit' ref_name='doc_form' :submitText='submitText'></AppForm>
        </div>
    </Dialog>
    

    <div class='accordion' v-if='docs && Object.keys(docs).length > 0'>
      <div class='accordion-item'  v-for='(item, label) in docs' :key='label' @click='toggle(label)'>
        <h3 class='accordion-header'>

          <div class='accordion-button'>
            Candidate {{label}} Documents
          </div>
        </h3>
        <div class='accordion-collapse' v-if='current==label'>
          <div class='accordion-body'>
            <DocItem v-if='item && item.length > 0' v-for='(fl,index) in item' :key='fl' :nameString='stringList' :fl='fl' :position='getIndex(label,index)'  @init-delete='deleteDoc'/>
            <div class='p-2' v-else>
              Document not available
            </div>
          </div>
        </div>

      </div>
      
    </div>
    <div class='p5 text-center mt-5 mb-3' v-else>
      <h4 class='mb-2'> 
        <i class='fa fa-exclamation-triangle'></i>
      </h4>
      <h4>No document found for candidate</h4>
    </div>
     
    </div>  
 `;

const resultTemplate = `
  <div>
    showing the results template
  </div>  
`;

const institutionTemplate = `
  <div class='med-school card mt-4 border mb-3'>
    <div class='card-header'>
      <div class='class-title fw-bold'>
        {{label}}
      </div>
    </div>
    <div class='card-body'>
      <div class='mt-1'>
        <div v-if='showForm'>
        <div class='d-flex justify-content-end mb-2'>
        <button class='btn btn-link text-primary' @click='showForm=false'><i class='fa fa-times'></i></button>
        </div>
          <AppForm :fields='wrap_fields'  @formSubmitted='initSubmit' :ref_name='item_ref' :submitText='submitText' />
        </div>
        <div class='d-flex justify-content-end pb-2'  v-else>
          <button class='btn btn-link' @click='showForm=true'>
          <i class='fa fa-plus'></i> Add New</button>
        </div>
      </div>
      <div v-if='accordion_items' class='accordion mb-3'>
        <Accordion @init-edit="editApp" @init-delete="deleteApp(index)" @toggle-open=' (index)=>updateOpened(index)' :opened='index == accordion_opened' v-for='(school,index) in accordion_items' :key='school.name' :heading='school.name' :index='index'  >
          <table class='table table-stripped table-bordered'>
            <tr v-for='(item,label) in school' :key='label'>
              <th>{{label}}</th>
              <td>{{item}}</td>
            </tr>
          </table>
        </Accordion>
      </div>
      <div v-else class='text-center pt-3 mb-3'>
        No {{form_name}} found
      </div>
    </div>
  </div>  
`;

const modalTemplate = `
  <div class="modal c-modal">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">{{modal_title}}</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" @click='$emit("modal-close")'></button>
        </div>
        <div class="modal-body">
          <slot></slot>
        </div>
        <div class="modal-footer" v-if='showFooter'>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary">{{actionName}}</button>
        </div>
      </div>
    </div>
  </div>
`;

const Dialog = {
	template: modalTemplate,
	props: {
		title: String,
		click: Function,
		modal_title: {
			type: String,
			required: true,
		},
		actionName: {
			type: String,
			default: "Apply",
		},
		showFooter: {
			type: Boolean,
			default: true,
		},
	},
	setup() {
		const config = inject("config");
		return {
			config,
		};
	},
	computed: {
		image() {
			const image_path = this.biodata.picture_path
				? this.biodata.picture_path
				: "assets/images/avatar.png";
			return this.config.baseurl + image_path;
		},
	},
};

const fieldTemplate = `
  <div>
      <label v-if='info.type!=="hidden"'>{{info.label}}</label>
      <input class='form-control' :name='info.name' type="text" v-if='info.type=="text"' v-model='currentValue' @change='emitChange' />
      <input class='form-control' :name='info.name' type="date" v-if='info.type=="date"' v-model='currentValue' @change='emitChange' />
      <textarea :name='info.name' class='form-control'  v-if='info.type=="longtext"' v-model='currentValue' @change='emitChange'></textarea>
      <select :name='info.name' v-model='currentValue' class='form-control' v-if='info.type=="select" ' @change='emitChange'>
        <option value='' selected>..select {{info.label}}</option>
        <option v-for='(opt,kk) in choices' :key='kk' :value='opt' > {{kk}}</option>
      </select>
      <input type="hidden" :name='info.name' :value='info.value' v-if='info.type =="hidden"'>
      <input type="file" :name='info.name' :value='info.value' v-if='info.type =="file"' class='form-control' >
  </div>
 `;

const formTemplate = `
  <div :class='containerClass'>
    <form @submit.prevent='submitFunction'  :ref='ref_name'>
      <Field class='mb-3' v-for='field in fields' :key='field.name' @fieldValueChanged='updateForm' :info='field'/>
      <div class='d-flex justify-content-end tify-content-end mb-3 mt-4'>
        <button class='btn btn-default' type='submit'>{{submitText}}</button>
      </div>
    </form>
  </div>
`;

const accordionTemplate = `
  <div class="accordion-item"  @click='toggleOpen(index)'>
    <h2 class="accordion-header" >
    <div class='position-relative'>
      <div class="accordion-button justify-content-between" type="button"  aria-expanded="true" aria-controls="collapseOne">
        <div>{{heading}}</div>

        <div class='action-item position-absolute px-2' v-if='includeAction'>
          <i @click.stop='$emit("init-edit", index)' class='fa fa-edit pr-2'></i>
          <i @click.stop='$emit("init-delete", index)' class='fa fa-times px-1'></i>
        </div>
      </div>
    </div>

    </h2>
    <div  class="accordion-collapse" aria-labelledby="headingOne" v-if='opened'>
      <div class="accordion-body p-4">
       <slot></slot>
      </div>
    </div>
  </div>
`;

const Accordion = {
	template: accordionTemplate,
	props: {
		heading: String,
		includeAction: {
			type: Boolean,
			default: true,
		},
		index: Number,
		opened: Boolean,
	},
	methods: {
		toggleOpen(index) {
			this.$emit("toggle-open", index);
		},
	},
};

const Field = {
	template: fieldTemplate,
	data() {
		return {
			currentValue: "",
		};
	},
	mounted() {
		if (this.info?.value) {
			this.currentValue = this.info.value;
		}
	},
	props: {
		info: {
			type: Object,
			required: true,
		},
	},
	computed: {
		choices() {
			if (!this.info.options || this.info.options.length == 0) {
				return [];
			}
			let result = {};
			if (Array.isArray(this.info.options)) {
				for (let i = 0; i < this.info.options.length; i++) {
					let item = this.info.options[i];
					result[item] = item;
				}
				return result;
			}
			return this.info.options;
		},
	},
	methods: {
		isSelected(key, value) {
			const result = this.currentValue == key || this.currentValue == value;
			if (result) {
			}
		},
		emitChange() {
			this.$emit("fieldValueChanged", this.info.name, this.currentValue);
		},
	},
	watch: {
		info(val) {
			if (val?.value) {
				this.currentValue = val.value;
			}
		},
	},
};

const AppForm = {
	template: formTemplate,
	components: { Field },
	props: {
		fields: {
			type: Object,
			required: true,
			default: [],
		},
		submitText: {
			type: String,
			default: "Add",
		},
		ref_name: String,
		containerClass: String,
		submitAction: Function,
		form: Object,
	},
	mounted() {
		this.initForm(this.fields);
	},
	data() {
		return {
			form_response: {},
			action_response: false,
		};
	},
	methods: {
		initForm(formItems) {
			formItems = Array.isArray(formItems)
				? formItems
				: Object.values(formItems);
			for (let i = 0; i < formItems.length; i++) {
				let item = formItems[i];
				this.form_response[item?.name] = item?.value;
			}
		},
		updateForm(key, val) {
			this.form_response[key] = val;
		},
		submitFunction() {
			this.$emit(
				"formSubmitted",
				this.ref_name,
				this.form_response,
				this.$refs[this.ref_name],
			);
		},
	},
	watch: {
		fields(val) {
			this.initForm(val);
		},
	},
};
const Institution = {
	template: institutionTemplate,
	components: {
		AppForm,
		Accordion,
	},
	props: {
		school: Object,
		fields: Object,
		label: String,
		form_name: String,
		item_ref: String,
		accordion_items: Array,
		item_key: String,
	},

	data() {
		return {
			showForm: false,
			accordion_opened: -1,
			selectedItem: -1,
		};
	},
	computed: {
		wrap_fields() {
			if (this.selectedItem < 0) {
				return Object.values(this.fields);
			}
			const app =
				this.selectedItem < 0 ? false : this.accordion_items[this.selectedItem];
			let keys = Object.keys(app);
			let result = { ...this.fields };
			for (var i = keys.length - 1; i >= 0; i--) {
				const key = keys[i];
				if (!result.hasOwnProperty(key)) continue;
				if (app) {
					result[key].value = app[key];
				}
			}
			return Object.values(result);
		},
		submitText() {
			return this.selectedItem >= 0 ? "Update" : "Add";
		},
	},
	methods: {
		initSubmit(name, response_values, form) {
			this.$emit(
				"init-update",
				this.item_key,
				name,
				response_values,
				form,
				this.selectedItem,
			);
			this.showForm = false;
		},
		editApp(index) {
			this.selectedItem = index;
			this.showForm = true;
		},
		deleteApp(index) {
			this.$emit("init-delete", this.item_ref, index, this.item_key);
		},
		updateOpened(index) {
			this.accordion_opened = this.accordion_opened == index ? -1 : index;
		},
	},
};

const profileTemplate = `
<div class='card border'>
  <div class='card-header'>
    <div class='card-title'><h5 class='text-center mb-2'>Candidate Profile <span v-if='showingTable' @click='showingTable=false' class='text-primary cursor-pointer fa fa-edit'></span></h5></div>
  </div>
  <div class='card-body'>
    <div class='row justify-content-center mt-1'>
      <div class='col-md-12  profile-biodata'>
        <table v-if='showingTable' class='table table-stripped biodata-table'>
          <tr>
             <td>Surname</td>
             <td>{{biodata.surname}}</td>
          </tr> 
          <tr>
             <td>Other Names</td>
             <td>{{biodata.other_name}}</td>
          </tr>
          <tr>
             <td>Maiden Name</td>
             <td>{{biodata.maiden_name}}</td>
          </tr>
          <tr>
             <td>Registration Number</td>
             <td>{{biodata?.registration_number}}</td>
          </tr>
          <tr>
             <td>Folio Number</td>
             <td>{{biodata?.cert_number}}</td>
          </tr>
          <tr>
             <td>Email</td>
             <td>{{biodata.email}}</td>
          </tr>
          <tr>
             <td>Phone</td>
             <td>{{biodata.phone}}</td>
          </tr>
          <tr>
             <td>Gender</td>
             <td>{{biodata.gender}}</td>
          </tr>
          <tr>
             <td>Faculty</td>
             <td>{{faculty_name}}</td>
          </tr>
          <tr>
             <td>Country</td>
             <td>{{biodata.country}}</td>
          </tr>
          <tr>
             <td>Mode of Entry</td>
             <td>{{biodata.entry_mode}}</td>
          </tr>
          <tr>
             <td>Date of Birth</td>
             <td>{{biodata.dob}}</td>
          </tr>
          <tr>
             <td>Nationality</td>
             <td>{{biodata.nationality}}</td>
          </tr>
          <tr>
             <td>Address</td>
             <td>{{biodata.address}}</td>
          </tr>
          <tr>
             <td>Postal Address</td>
             <td>{{biodata.postal_address}}</td>
          </tr>
          <tr>
             <td>Country</td>
             <td>{{biodata.country}}</td>
          </tr>
          <tr>
             <td>Entry Mode</td>
             <td>{{biodata.entry_mode}}</td>
          </tr>
          <tr>
             <td>Change of Name</td>
             <td>{{biodata.change_of_name}}</td>
          </tr>
          <tr>
             <td>Speciality</td>
             <td>{{sub_speciality}}</td>
          </tr>
          <tr>
             <td>Full Registration Date with MDCN</td>
             <td>{{biodata.full_registration_date}}</td>
          </tr>
          <tr>
             <td>NYSC Discharge/Exemption Date</td>
             <td>{{biodata.nysc_discharge_or_exemption}}</td>
          </tr>
          <tr>
             <td>Accredited Training Program</td>
             <td>{{biodata.accredited_training_program}}</td>
          </tr>
          <tr>
             <td>Year of Fellowship</td>
             <td>{{biodata.year_of_fellowship}}</td>
          </tr>
        </table> 
        <AppForm v-else :fields='biodataUpdateFields' @formSubmitted='initBiodataAction'ref_name='biodata' submitText='Update' />
      </div>
    </div>
  </div>
</div>

`;

const Profile = {
	template: profileTemplate,
	components: {
		AppForm,
	},
	data() {
		return {
			showingTable: true,
		};
	},
	props: {
		biodata: Object,
		candidate_form: Object,
		faculty_name: String,
		sub_speciality: String,
	},
	computed: {
		biodataUpdateFields() {
			let result = { ...this.candidate_form };
			let keys = Object.keys(result);
			for (var i = keys.length - 1; i >= 0; i--) {
				const key = keys[i];
				if (!result.hasOwnProperty(key)) continue;
				result[key].value = this.biodata[key];
			}
			return result;
		},
	},
	methods: {
		initBiodataAction(name, response_values, form) {
			this.$emit("init-update", "biodata", name, response_values, form, 0);
			setTimeout(() => (this.showingTable = true), 500);
		},
	},
};

const Biodata = {
	template: biodataTemplate,
	components: {
		Institution,
		AppForm,
		Accordion,
		Profile,
	},
	props: {
		biodata: Object,
		med_form: Object,
		inst_form: Object,
		post_form: Object,
		candidate_form: Object,
		faculty_name: String,
		sub_speciality: String,
	},
	data() {
		return {
			showUpload: false,
			showMedicalForm: false,
			med_accordion_opened: -1,
			med_selectedForm: -1,
		};
	},
	setup() {
		const config = inject("config");
		return {
			config,
		};
	},
	computed: {
		biodataUpdateFields() {
			let result = { ...this.candidate_form };
			let keys = Object.keys(result);
			for (var i = keys.length - 1; i >= 0; i--) {
				const key = keys[i];
				if (!result.hasOwnProperty(key)) continue;
				result[key].value = this.biodata[key];
			}
			return result;
		},
		submitText() {
			return this.med_selectedForm >= 0 ? "Update" : "Add";
		},
		wrap_med_form() {
			if (this.med_selectedForm < 0) {
				return Object.values(this.med_form);
			}
			const app =
				this.med_selectedForm < 0
					? false
					: this.biodata.medical_school[this.med_selectedForm];
			let keys = Object.keys(app);
			let result = { ...this.med_form };
			for (var i = keys.length - 1; i >= 0; i--) {
				const key = keys[i];
				if (!result.hasOwnProperty(key)) continue;
				if (app) {
					result[key].value = app[key];
				}
			}
			return Object.values(result);
		},
		pic_frame_class() {
			return this.showUpload
				? "justify-content-between"
				: "justify-content-center";
		},
		image() {
			const image_path = this.biodata.picture_path
				? this.biodata.picture_path
				: "assets/images/no-picture.png";
			return this.config.baseurl + image_path;
		},
	},
	methods: {
		initBiodataUpdate(key, name, response_values, form, selected) {
			this.$emit("init-update", key, name, response_values, form, selected);
			// setTimeout(() => this.showingTable = true, 500);
		},
		editApp(form, index) {
			if (form == "med") {
				this.med_selectedForm = index;
				this.showMedicalForm = true;
			}
		},
		deleteApp(form, index, key) {
			this.$emit("init-delete", key, index);
		},
		updateOpened(formType, index) {
			if (formType == "med") {
				this.med_accordion_opened =
					index == this.med_accordion_opened ? -1 : index;
			}
		},
		initAccordionSubmit(key, name, response_values, form, selected = -1) {
			// console.log(jQuery);
			this.$emit("init-update", key, name, response_values, form, selected);
			// this.showMedicalForm = false;
		},
		async updateFile() {
			const file = this.$refs["photo-file"];
			let formData = new FormData();
			formData.append("picture_path", file.files[0]);
			const path = `${this.config.baseurl}mc/update/candidate/${this.biodata.id}`;
			let config = { headers: { "Content-Type": "multipart/form-data" } };
			try {
				let res = await axios.post(path, formData, config);
				let dt = typeof res.data === "object" ? res.data : JSON.parse(res.data);
				if (dt.status) {
					let data = dt.data;
					this.$emit("init-item-update", "biodata", data, 1000000);
				} else {
					this.$emit("notice", false, dt.message);
				}
			} catch (err) {
				this.$emit("notice", false, err.message);
			}
		},
	},
};

const Application = {
	template: appTemplate,
	setup() {
		let config = inject("config");
		return {
			config,
		};
	},
	components: {
		AppForm,
		Dialog,
	},
	props: {
		applications: {
			type: Array,
		},
		biodata: {
			type: Object,
			required: true,
		},
		form: Object,
	},
	data() {
		return {
			currentOpened: -1,
			showForm: false,
			selectedForm: -1,
			fields: {},
		};
	},
	mounted() {
		this.fields = this.form;
	},
	methods: {
		clearForm() {
			this.showForm = false;
			this.selectedForm = -1;
		},
		showNew() {
			this.$nextTick(() => {
				this.selectedForm = -1;
				this.showForm = !this.showForm;
			});
		},
		editApp(index) {
			this.selectedForm = index;
			this.showForm = false;
			this.$nextTick(() => {
				this.showForm = true;
			});
		},
		deleteApp(index) {
			this.$emit("init-delete", "applications", index);
		},
		initSubmit(name, response_values, form) {
			// console.log(jQuery);
			this.$emit(
				"init-update",
				"applications",
				name,
				response_values,
				form,
				this.selectedForm,
			);
			this.showForm = false;
		},
		toggleOpen(index) {
			this.currentOpened = this.currentOpened == index ? -1 : index;
		},
		fetchDisplay(id, field) {
			let options = this.form[field].options;
			let keys = Object.keys(options);
			let values = Object.values(options);
			let index = values.indexOf(id);
			if (index === -1) {
				return "";
			}
			return keys[index];
		},
	},
	computed: {
		submitText() {
			return this.selectedForm >= 0 ? "Update" : "Add";
		},
		examFields() {
			const excludes = [
				"code",
				"candidate_id",
				"previous_attempts",
				"time_created",
			];
			if (this.selectedForm < 0) {
				return Object.values(this.fields);
			}
			const app = this.applications[this.selectedForm];
			let keys = Object.keys(app);
			let result = {};
			for (var i = keys.length - 1; i >= 0; i--) {
				const key = keys[i];
				if (!this.fields.hasOwnProperty(key) || excludes.includes(key))
					continue;
				if (app) {
					result[key] = this.fields[key];
					result[key].value = app[key];
				}
			}
			return Object.values(result);
		},
		exam_items() {
			const excludes = [
				"code",
				"candidate_id",
				"previous_attempts",
				"time_created",
				"id",
				"other_details",
			];
			let result = [];
			if (!this.applications) {
				return result;
			}
			const passed = ["No", "Yes", "Primary Exemption"];
			for (let i = 0; i < this.applications.length; i++) {
				let item = this.applications[i];
				let structure = { ...item };
				excludes.forEach((key) => delete structure[key]);
				let pindex = parseInt(structure["passed"]);
				pindex = Math.abs(pindex) + (pindex < 0 ? 1 : 0);
				structure["passed"] = passed[pindex];
				structure["exam_date"] = this.fetchDisplay(
					structure["exam_date"],
					"exam_date",
				);
				structure["exam_center"] = this.fetchDisplay(
					structure["exam_center_id"],
					"exam_center_id",
				);
				delete structure["exam_center_id"];
				result.push(structure);
			}
			return result;
		},
		formText() {
			return this.showForm ? "Hide" : "Add new";
		},
	},
	watch: {
		form() {
			this.fields = this.form;
		},
	},
};

const Documents = {
	template: documentTemplate,
	components: {
		DocItem,
		AppForm,
		Dialog,
	},
	data() {
		return {
			showForm: false,
			selectedItem: -1,
			current: "",
		};
	},
	props: {
		docs: Object,
		fullname: String,
		fields: Object,
		applications: {
			type: Array,
			required: true,
		},
	},
	computed: {
		submitText() {
			return this.selectedItem >= 0 ? "Update" : "Add";
		},
		formText() {
			return this.showForm ? "Hide" : "Add new";
		},
		docFields() {
			const excludes = [];
			if (this.selectedItem < 0) {
				return Object.values(this.fields);
			}
			const app = this.selectedItem < 0 ? false : this.docs[this.selectedItem];
			let keys = Object.keys(app);
			let result = {};
			for (var i = keys.length - 1; i >= 0; i--) {
				const key = keys[i];
				if (!result.hasOwnProperty(key) || excludes.includes(key)) continue;
				if (app) {
					result[key].value = app[key];
				}
			}
			return Object.values(result);
		},
		stringList() {
			if (this.fullname) {
				return this.fullname.toLowerCase().split(" ");
			}
			return [];
		},
	},
	methods: {
		toggle(index) {
			this.current = this.current == index ? "" : index;
		},
		getIndex(label, index) {
			return `${label}:${index}`;
		},
		showNew() {
			this.$nextTick(() => {
				this.selectedItem = -1;
				this.showForm = !this.showForm;
			});
		},
		initSubmit(name, response_values, form) {
			this.$emit(
				"init-update",
				"documents",
				name,
				response_values,
				form,
				this.selectedItem,
			);
			this.showForm = false;
		},
		editApp(index) {
			this.selectedItem = index;
			this.showForm = true;
		},
		deleteDoc(key, index) {
			this.$emit("init-delete", key, index, key);
		},
	},
};
const Overview = {
	template: overviewTemplate,
	components: {
		OverviewField,
	},
	setup() {
		const config = inject("config");
		return {
			config,
		};
	},
	props: {
		candidate: Object,
		faculty_name: String,
		sub_speciality: String,
	},
	computed: {
		image() {
			const image_path = this.candidate.biodata.picture_path
				? this.candidate.biodata.picture_path
				: "assets/images/no-picture.png";
			return this.config.baseurl + image_path;
		},
		appOrdered() {
			let result = {};
			for (var i = this.candidate.applications.length - 1; i >= 0; i--) {
				let app = this.candidate.applications[i];
				let type = app.application_type;
				if (!type) {
					continue;
				}
				if (!result.hasOwnProperty(type)) {
					result[type] = { dates: [], passed: [] };
				}
				if (app.exam_date && app.exam_date.length > 0) {
					result[type].dates.push(
						this.getExamDisplay(app.exam_date, app.exam_center_id),
					);
				}

				if (app.passed && (app.passed == "1" || app.passed == "-1")) {
					let text = this.getExamDisplay(
						app.exam_date,
						app.exam_center_id,
						app.passed,
					);
					result[type].passed.push(text);
				}
			}
			return result;
		},
	},
	methods: {
		listApplication(dates) {
			if (!(dates && (dates = dates.filter((x) => x && x.length > 0)))) {
				return "";
			}
			let result = [...dates];
			result.sort((first, second) => {
				let a = parseInt(first);
				let b = parseInt(second);
				if (first === second) return 0;
				if (a > b) return -1;
				if (a < b) return 1;
				if (a == b) {
					firstLetter = first[first.length - 1];
					secondLetter = second[first.length - 1];
					if (firstLetter > secondLetter) return 1;
					if (firstLetter < secondLetter) return -1;
				}
			});
			return result.join(" | ");
		},
		showAlert() {
			window.alert("functionality not implemented yet");
		},
		getExamDisplay(date, center, passed) {
			const centers = this.candidate.forms.app["exam_center_id"].options;
			const exams = this.candidate.forms.app["exam_date"].options;
			let centerKey = Object.keys(centers);
			let centerValues = Object.values(centers);
			let examKeys = Object.keys(exams);
			let examValues = Object.values(exams);
			let centerIndex = centerValues.indexOf(center);
			let examIndex = examValues.indexOf(date);
			if (examIndex == -1) {
				return "";
			}
			let result = examKeys[examIndex];
			extra = "";
			if (centerIndex !== -1) {
				extra = centerKey[centerIndex];
			}

			if (passed == "-1") {
				extra += extra ? " - Exemption " : " Exemption ";
			}
			if (extra) {
				result += ` (${extra})`;
			}
			return result;
		},
	},
};

const Results = {
	template: resultTemplate,
};

const appComponent = {
	template: mainTemplate,
	components: {
		Overview,
		Biodata,
		Application,
		Documents,
		Results,
	},
	setup() {
		const config = inject("config");
		return {
			config,
		};
	},
	props: {
		candidate: Object,
	},
	computed: {
		docFields() {
			let applications = this.candidate.applications;
			let form = this.candidate.forms.docForm;
			let options = {};
			for (var i = applications.length - 1; i >= 0; i--) {
				let app = applications[i];
				let val =
					app.application_type +
					" " +
					this.fetchDisplay(app.exam_date, "app", "exam_date");
				options[val] = app.id;
			}
			form.application_id.options = options;
			return form;
		},
		faculty_name() {
			const faculty = this.candidate.forms.candidate["faculty_id"];
			let keys = Object.keys(faculty.options);
			let values = Object.values(faculty.options);
			let index = values.indexOf(this.candidate.biodata.faculty_id);
			if (index === -1) {
				return "";
			}
			return keys[index];
		},
		sub_speciality() {
			const sub_speciality = this.candidate.forms.candidate["sub_speciality"];
			let keys = Object.keys(sub_speciality.options);
			let values = Object.values(sub_speciality.options);
			let index = values.indexOf(this.candidate.biodata.sub_speciality);
			if (index === -1) {
				return "";
			}
			return keys[index];
		},
		loaderPath() {
			return this.config.baseurl + "assets/images/loader.gif";
		},
		fullname() {
			const result =
				this.candidate.biodata.surname +
				" " +
				this.candidate.biodata.other_name;
			return result;
		},
		statusClass() {
			return this.notification.status ? "alert-success" : "alert-danger";
		},
	},
	data() {
		return {
			candidateData: [],
			active: 0,
			loading: false,
			tabs: ["Overview", "Bio Data", "Application", "Documents", "Result"],
			notification: {
				status: false,
				message: "",
			},
		};
	},
	methods: {
    goback(){
      window.history.back();
    },
		fetchDisplay(id, entry, field) {
			let options = this.candidate.forms[entry][field].options;
			let keys = Object.keys(options);
			let values = Object.values(options);
			let index = values.indexOf(id);
			if (index === -1) {
				return "";
			}
			return keys[index];
		},
		setLoading(val) {
			this.loading = val;
		},
		notify(status, message, stay = false) {
			this.notification.status = status;
			this.notification.message = message;
			const outer = this;
			if (!stay) {
				setTimeout(() => {
					outer.notification.message = "";
				}, 5000);
			}
		},
		async deleteEntity(model, index) {
			let field = this.getModel(model);
			let item_id = this.getCurrentID(model, index); // this.candidate[field][index];
			let entity = field.split(":");
			entity = entity[entity.length - 1];
			let path = this.config.baseurl + "delete/" + entity + "/" + item_id;
			if (!window.confirm("Are you sure you want to delete it?")) {
				return;
			}
			try {
				this.setLoading(true);
				let config = {
					headers: { "Content-Type": "application/x-www-form-urlencoded" },
				};
				let res = await axios.get(path, config);
				let dt = typeof res.data == "object" ? res.data : JSON.parse(res.data);
				if (dt.status) {
					this.updateItem(this.candidate, model, null, index, true);
					this.notify(true, dt.message);
				} else {
					this.notify(false, dt.message);
				}
			} catch (err) {
				console.log(err);
				this.notify(false, "unable to delete item");
			} finally {
				this.setLoading(false);
			}
		},
		uploadProgress(event) {
			const { loaded, total } = event;
			let percent = Math.floor((loaded * 100) / total);
			if (percent < 100) {
				this.notify(true, `upload in progress: ${percent}%`, true);
			}
		},
		getModel(model) {
			const modelDict = {
				applications: "application",
				"biodata:post_training": "biodata:institution",
				biodata: "candidate",
				documents: "document",
			};
			if (model in modelDict) {
				return modelDict[model];
			}
			return model;
		},
		getCurrentID(label, index) {
			let arr = label.split(":");
			let temp = this.candidate[arr[0]];
			if (arr.length == 2) {
				temp = temp[arr[1]];
			}
			let indices = (index + "").split(":");
			let result = temp[indices[0]];
			if (indices.length == 2) {
				result = result[indices[1]];
			}
			return result.id;
		},
		async submitForm(model, name, response_values, form, selectedForm = -1) {
			this.setLoading(true);
			let item_id = "";
			let item;
			let display = this.getModel(model);
			let entity = display.split(":");
			entity = entity[entity.length - 1];
			if (selectedForm >= 0 && model !== "biodata") {
				item_id = this.getCurrentID(model, selectedForm);
			}
			if (model == "biodata") {
				item_id = this.candidate.biodata.id;
			}
			let path =
				this.config.baseurl +
				(selectedForm < 0
					? "mc/add/" + entity + "/1"
					: "mc/update/" + entity + "/" + item_id);
			let payload = response_values;
			let formUploadField = form.querySelector('input[type="file"]');
			let hasUpload = formUploadField && "value" in formUploadField;
			let config = {
				headers: { "Content-Type": "application/x-www-form-urlencoded" },
			};
			if (hasUpload) {
				payload = new FormData(form);
				config = {
					headers: { "Content-Type": "multipart/form-data" },
					onUploadProgress: this.uploadProgress,
				};
			}
			try {
				let res = await axios.post(path, payload, config);
				this.setLoading(false);
				let dt = typeof res.data == "object" ? res.data : JSON.parse(res.data);
				if (dt.status) {
					let data = dt.data ? dt.data : response_values;
					this.updateEntity(model, data, selectedForm);
					this.notify(true, dt.message);
				} else {
					this.notify(false, dt.message);
				}
			} catch (error) {
				console.log(error.message);
				this.notify(false, "unable to complete operation");
			} finally {
				this.setLoading(false);
			}
		},
		updateItem(item, accessor, value, index = -1, remove = false) {
			let accesses = accessor.split(":");
			const isEmpty =
				!item[accesses[0]] ||
				item[accesses[0]].length == 0 ||
				Object.keys(item[accesses[0]]).length == 0;
			if (isEmpty) {
				item[accesses[0]] = [];
				if (accesses.length == 2) {
					item[accesses[0]] = {};
					item[accesses[0]][accesses[1]] = [];
				}
			}
			let currentRef = item[accesses[0]];
			for (var i = 1; i < accesses.length; i++) {
				currentRef = currentRef[accesses[i]];
			}
			let indices = (index + "").split(":");
			if (indices.length == 2) {
				currentRef = currentRef[indices[0]];
				index = indices[1];
			}

			if (remove) {
				currentRef.splice(index, 1);
				return;
			}
			if (index !== -1) {
				if (Array.isArray(currentRef)) {
					currentRef[index] = value;
				} else {
					currentRef = { ...currentRef, ...data };
				}
			} else {
				currentRef.push(value);
			}
		},
		updateEntity(model, data, isUpdate) {
			// const display = this.getModel(model);
			if (model === "biodata") {
				Object.assign(this.candidate.biodata, data);
				// this.candidate.biodata = {...this.candidate.biodata, ...data};
				return;
			}
			if (model == "documents") {
				let appType = "General";
				if (data.application_id) {
					let tt = this.candidate.applications.filter(
						(item) => item.id == data.application_id,
					);
					appType = tt[0].application_type;
				}
				model += ":" + appType;
			}
			this.updateItem(this.candidate, model, data, isUpdate);
		},
	},
};

let app = createApp({
	components: {
		App: appComponent,
	},
	setup() {
		const base = document.getElementById("baseurl").value;
		let candidateData = atob(document.getElementById("candidate_data").value);
		if (candidateData && candidateData.trim()) {
			candidateData = reactive(JSON.parse(candidateData.trim()));
		}

		const config = reactive({
			baseurl: base,
		});
		provide("config", config);
		return {
			candidateData,
		};
	},
}).mount("#app");
