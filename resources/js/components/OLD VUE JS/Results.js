export const resultTemplate = `
  <div>
    <h4 class='text-center p-3'>Candidate Results</h4>
    <div class='table-responsive'>
      <table class='table table-bordered'>
        <thead><tr><th>Exam</th><th>Score</th></tr></thead>
        <tbody>
          <tr v-for='res in results' :key='res.id'>
            <td>{{res.exam}}</td>
            <td>{{res.score}}</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
`;

export const Results = {
  template: resultTemplate,
  props: ["candidate"],
  computed: {
    results() {
      return this.candidate?.results || [];
    }
  }
};

