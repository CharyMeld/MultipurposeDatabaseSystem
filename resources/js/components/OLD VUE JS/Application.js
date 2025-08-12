import React, { useState } from 'react';

const APPLICATION_TYPES = ['Primary', 'Membership', 'Fellowship'];
const MODE_OF_ENTRY = [
  'Examination',
  'Fellowship by Election',
  'Fellowship by Progression',
  "Fellowship by Grandfather's Clause",
  'Honorary Fellowship',
  'International Fellowship',
];
const EXAM_DATES = ['A1982', 'A1983', 'A1984', /*...*/ 'A2025', 'B1982', /*...*/ 'B2025'];
const EXAM_CENTERS = [
  'Ibadan',
  'Accra',
  'Monrovia',
  'Abuja',
  'Zaria',
  'Enugu',
  'Kaduna',
  'Freetown',
];
const PASSED_OPTIONS = ['Yes', 'No', 'Primary Exemption'];

function ApplicationForm({ onAdd }) {
  const [form, setForm] = useState({
    applicationType: '',
    modeOfEntry: '',
    examNumber: '',
    examDate: '',
    examCenter: '',
    dateApplied: '',
    dateApproved: '',
    passed: '',
  });

  const handleChange = (e) => {
    const { name, value } = e.target;
    setForm((f) => ({ ...f, [name]: value }));
  };

  const handleSubmit = (e) => {
    e.preventDefault();
    if (!form.applicationType) {
      alert('Please select Application Type');
      return;
    }
    onAdd(form);
    setForm({
      applicationType: '',
      modeOfEntry: '',
      examNumber: '',
      examDate: '',
      examCenter: '',
      dateApplied: '',
      dateApproved: '',
      passed: '',
    });
  };

  return (
    <form onSubmit={handleSubmit} style={{ border: '1px solid #ccc', padding: 12, marginBottom: 20 }}>
      <h3>New Application Entry</h3>

      <label>
        Application Type:
        <select name="applicationType" value={form.applicationType} onChange={handleChange} required>
          <option value="">--Select--</option>
          {APPLICATION_TYPES.map((type) => (
            <option key={type} value={type}>{type}</option>
          ))}
        </select>
      </label>
      <br />

      <label>
        Mode of Entry:
        <select name="modeOfEntry" value={form.modeOfEntry} onChange={handleChange} required>
          <option value="">--Select--</option>
          {MODE_OF_ENTRY.map((mode) => (
            <option key={mode} value={mode}>{mode}</option>
          ))}
        </select>
      </label>
      <br />

      <label>
        Exam Number:
        <input
          type="text"
          name="examNumber"
          value={form.examNumber}
          onChange={handleChange}
          required
        />
      </label>
      <br />

      <label>
        Exam Date:
        <select name="examDate" value={form.examDate} onChange={handleChange} required>
          <option value="">--Select--</option>
          {EXAM_DATES.map((date) => (
            <option key={date} value={date}>{date}</option>
          ))}
        </select>
      </label>
      <br />

      <label>
        Exam Center:
        <select name="examCenter" value={form.examCenter} onChange={handleChange} required>
          <option value="">--Select--</option>
          {EXAM_CENTERS.map((center) => (
            <option key={center} value={center}>{center}</option>
          ))}
        </select>
      </label>
      <br />

      <label>
        Date Applied:
        <input
          type="date"
          name="dateApplied"
          value={form.dateApplied}
          onChange={handleChange}
          required
        />
      </label>
      <br />

      <label>
        Date Approved:
        <input
          type="date"
          name="dateApproved"
          value={form.dateApproved}
          onChange={handleChange}
          required
        />
      </label>
      <br />

      <label>
        Passed:
        <select name="passed" value={form.passed} onChange={handleChange} required>
          <option value="">--Select--</option>
          {PASSED_OPTIONS.map((opt) => (
            <option key={opt} value={opt}>{opt}</option>
          ))}
        </select>
      </label>
      <br />

      <button type="submit">Add Application</button>
    </form>
  );
}

function ApplicationItem({ application, onEdit, onDelete }) {
  const [showDetails, setShowDetails] = useState(false);

  return (
    <div style={{ border: '1px solid #aaa', marginBottom: 8, padding: 8 }}>
      <div
        style={{ cursor: 'pointer', fontWeight: 'bold' }}
        onClick={() => setShowDetails((show) => !show)}
      >
        {application.applicationType} - Exam#: {application.examNumber}
      </div>
      <div>
        <button onClick={() => onEdit(application)}>Edit</button>{' '}
        <button onClick={() => onDelete(application)}>Delete</button>
      </div>
      {showDetails && (
        <div style={{ marginTop: 8, backgroundColor: '#f9f9f9', padding: 8 }}>
          <div><b>Mode of Entry:</b> {application.modeOfEntry}</div>
          <div><b>Exam Date:</b> {application.examDate}</div>
          <div><b>Exam Center:</b> {application.examCenter}</div>
          <div><b>Date Applied:</b> {application.dateApplied}</div>
          <div><b>Date Approved:</b> {application.dateApproved}</div>
          <div><b>Passed:</b> {application.passed}</div>
        </div>
      )}
    </div>
  );
}

export default function ApplicationList() {
  const [applications, setApplications] = useState([]);
  const [editingApp, setEditingApp] = useState(null);

  // Add new application
  const addApplication = (app) => {
    if (editingApp) {
      // Update existing
      setApplications((apps) =>
        apps.map((a) => (a === editingApp ? { ...app } : a))
      );
      setEditingApp(null);
    } else {
      setApplications((apps) => [...apps, app]);
    }
  };

  // Edit application
  const editApplication = (app) => {
    setEditingApp(app);
  };

  // Delete application
  const deleteApplication = (app) => {
    if (window.confirm('Are you sure you want to delete this application?')) {
      setApplications((apps) => apps.filter((a) => a !== app));
    }
  };

  // Group applications by Application Type
  const groupedApps = APPLICATION_TYPES.reduce((acc, type) => {
    acc[type] = applications.filter((a) => a.applicationType === type);
    return acc;
  }, {});

  return (
    <div>
      <ApplicationForm onAdd={addApplication} key={editingApp ? 'edit' : 'new'} />

      {APPLICATION_TYPES.map((type) => (
        <div key={type} style={{ marginBottom: 30 }}>
          <h2>{type} Applications</h2>
          {groupedApps[type].length === 0 ? (
            <p>No {type.toLowerCase()} applications added yet.</p>
          ) : (
            groupedApps[type].map((app, i) => (
              <ApplicationItem
                key={i}
                application={app}
                onEdit={editApplication}
                onDelete={deleteApplication}
              />
            ))
          )}
        </div>
      ))}
    </div>
  );
}

