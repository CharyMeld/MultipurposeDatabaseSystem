export const Overview = {
  props: {
    candidate: {
      type: Object,
      required: true,
    },
    faculty_name: {
      type: String,
      default: '',
    },
    sub_speciality: {
      type: String,
      default: '',
    },
    medicalSchools: {
      type: Array,
      default: () => [],
    },
    institutions: {
      type: Array,
      default: () => [],
    },
    postgraduates: {
      type: Array,
      default: () => [],
    },
  },
  template: `
   <!-- PASSPORT -->
      <div class="candidate-image-container mb-4" style="text-align: left;">
      <div
        class="border rounded-2 d-inline-block"
        style="width: 150px; height: 150px; overflow: hidden; background-color: #f8f9fa;"
      >
        <img
          :src="candidate.passport ? candidate.passport : 'https://via.placeholder.com/150'"
          alt="Passport"
          style="width: 100%; height: 100%; object-fit: cover; border-radius: 6px;"
        />
      </div>
    </div>


      <!-- CANDIDATE DETAILS -->
      <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white">Candidate Details</div>
        <div class="card-body candidate-overview overflow-x">
          <table>
            <tbody>
              <tr>
                <th>Registration No:</th>
                <td>{{ candidate.registration_number || '' }}</td>
                <th>Surname:</th>
                <td>{{ candidate.surname || '' }}</td>
              </tr>
              <tr>
                <th>Other Name:</th>
                <td>{{ candidate.other_name || '' }}</td>
                <th>Maiden Name:</th>
                <td>{{ candidate.maiden_name || '' }}</td>
              </tr>
              <tr>
                <th>Entry Mode:</th>
                <td>{{ candidate.entry_mode || '' }}</td>
                <th>Country:</th>
                <td>{{ candidate.country || '' }}</td>
              </tr>
              <tr>
                <th>Date of Birth:</th>
                <td>{{ candidate.dob || '' }}</td>
                <th>Gender:</th>
                <td>{{ candidate.gender || '' }}</td>
              </tr>
              <tr>
                <th>Nationality:</th>
                <td>{{ candidate.nationality || '' }}</td>
                <th>Fellowship Type:</th>
                <td>{{ candidate.fellowship_type || '' }}</td>
              </tr>
              <tr>
                <th>Faculty:</th>
                <td>{{ faculty_name || candidate.faculty_id || '' }}</td>
                <th>Sub Speciality:</th>
                <td>{{ sub_speciality || candidate.sub_speciality || '' }}</td>
              </tr>
              <tr>
                <th>NYSC Discharge/Exemption:</th>
                <td>{{ candidate.nysc_discharge_or_exemption || '' }}</td>
                <th>Accredited Training Program:</th>
                <td>{{ candidate.accredited_training_program || '' }}</td>
              </tr>
              <tr>
                <th>Email:</th>
                <td>{{ candidate.email || '' }}</td>
                <th>Phone:</th>
                <td>{{ candidate.phone || '' }}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- MEDICAL SCHOOL -->
      <div v-if="medicalSchools.length" class="card shadow-sm mb-4">
        <div class="card-header bg-success text-white">Medical School Attended</div>
        <div class="card-body">
          <ul class="list-group">
            <li
              v-for="(school, index) in medicalSchools"
              :key="index"
              class="list-group-item d-flex justify-content-between align-items-center"
            >
              {{ school.name }}
            </li>
          </ul>
        </div>
      </div>

      <!-- INSTITUTIONS ATTENDED -->
      <div v-if="institutions.length" class="card shadow-sm mb-4">
        <div class="card-header bg-info text-white">Institution(s) Attended</div>
        <div class="card-body">
          <ul class="list-group">
            <li
              v-for="(institution, index) in institutions"
              :key="index"
              class="list-group-item d-flex justify-content-between align-items-center"
            >
              {{ institution.name }}
            </li>
          </ul>
        </div>
      </div>

      <!-- POSTGRADUATE TRAINING -->
      <div v-if="postgraduates.length" class="card shadow-sm mb-4">
        <div class="card-header bg-warning text-dark">Postgraduate Training</div>
        <div class="card-body">
          <ul class="list-group">
            <li
              v-for="(pg, index) in postgraduates"
              :key="index"
              class="list-group-item d-flex justify-content-between align-items-center"
            >
              {{ pg.field }}
            </li>
          </ul>
        </div>
      </div>
    </div>
  `,
};

