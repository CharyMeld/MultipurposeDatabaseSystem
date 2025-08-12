<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UpdateMenuPathsSeeder extends Seeder
{
    public function run(): void
    {
        $paths = [
            // 🏠 Home
            'Dashboard' => 'dashboard',
            'Log Out' => 'logout',

            // 👤 User Management
            'Users' => 'users.index',
            'Roles' => 'roles.index',

            // 👥 Candidates
            'Candidates' => 'candidates.index',
            'PIT' => 'candidates.pit',
            'Sub Speciality' => 'specialties.sub.index',
            'Revision Courses' => 'courses.revision.index',
            'DMH' => 'dmh.index',
            'Fellowship by Election' => 'fellowships.election.index',

            // 🏢 Admin Office
            'Accreditation' => 'admin.accreditation.index',
            'Institutions' => 'institutions.index',

            // 📄 Office Documents
            'Emails' => 'documents.emails',
            'Attendance' => 'documents.attendance',
            'Staff' => 'documents.staff',
            'Memo' => 'documents.memo',

            // 🧑‍🏫 Exam Office
            'Examiner Attendance' => 'exams.attendance',
            'Results' => 'exams.results',
            'Examiner Joint Report' => 'exams.joint_report',
            'Exam Script' => 'exams.scripts',
            'Chief Examiners Report' => 'exams.reports.chief',

            // 📘 AGSM Resources
            'Annual Lectures' => 'agsm.lectures',
            'Pictures/Gallery' => 'agsm.gallery',
            'Meetings' => 'agsm.meetings',
            'AGM' => 'agsm.agm',
            'AGM Minutes (Black Book)' => 'agsm.minutes',
            'AGM Meetings' => 'agsm.agm_meetings',
            'Journals' => 'journals.index',
            'WAJM' => 'journals.wajm',

            // 💰 Finance
            'Audit Office' => 'finance.audit.office',
            'Audits' => 'finance.audits',
            'Account Office' => 'finance.account',
            'Financial Docs' => 'finance.documents',
            'Academics & Exams Records' => 'finance.academic_records',
            'Events & Meeting Expenses' => 'finance.expenses.events',
            'Admin & Legal Docs' => 'finance.legal_documents',
            'Operations & Misc.' => 'finance.misc',

            // 📋 Head of Admin
            'Prize and Awards' => 'headadmin.prizes',
            'Properties' => 'headadmin.properties',
            'Biz Entities' => 'headadmin.business',
            'Fin, Audits & Grants' => 'headadmin.finance',
            'Comm. & Gov.' => 'headadmin.governance',
            'Reports' => 'headadmin.reports',
            'Gen Admin & MExecutives' => 'headadmin.general',

            // 🧑 Officials
            'President' => 'officials.president',
            'Secretary General' => 'officials.secretary',
            'Registrar' => 'officials.registrar',
            'College Treasurer' => 'officials.treasurer',
            'Misc' => 'officials.misc',

            // ⚙️ Settings
            'Faculty' => 'settings.faculty',
            'Examination' => 'settings.exams',
            'Exam Centers' => 'settings.exam_centers',
            'Volume' => 'settings.volume',
            'Document Category' => 'settings.document_category',
            'Statistics' => 'settings.statistics',
            ];

        foreach ($paths as $menuTitle => $routeName) {
            DB::table('menus')
                ->where('title', $menuTitle)
                ->update(['route_name' => $routeName]);
        }

        $this->command->info('Menu route names updated successfully.');
    }
}


