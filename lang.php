<?php
declare(strict_types=1);

// Multi-language system: English (en) and Khmer (kh)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if language switch is requested via GET
if (isset($_GET['lang']) && is_string($_GET['lang']) && in_array($_GET['lang'], ['en', 'kh'], true)) {
    $_SESSION['lang'] = $_GET['lang'];
    setcookie('lang', $_GET['lang'], time() + (86400 * 30), "/");
}

// Determine active language
$current_lang = (string)($_SESSION['lang'] ?? ($_COOKIE['lang'] ?? 'en'));
if (!in_array($current_lang, ['en', 'kh'], true)) {
    $current_lang = 'en';
}

// Translations dictionary
$translations = [
    'en' => [
        // Brand & General
        'app_name' => 'DepreSys',
        'app_subtitle' => 'Depreciation & Asset Management',
        'lang_en' => 'English',
        'lang_kh' => 'ភាសាខ្មែរ (Khmer)',
        'language' => 'Language',
        'theme' => 'Theme',
        'search_placeholder' => 'Search records...',
        'admin' => 'Administrator',
        'account_settings' => 'Account Settings',
        'sign_out' => 'Sign Out',
        'welcome' => 'Welcome',
        'today' => 'Today',
        'good_morning' => 'Good morning',
        'good_afternoon' => 'Good afternoon',
        'good_evening' => 'Good evening',
        'cancel' => 'Cancel',
        'save' => 'Save',
        'save_changes' => 'Save Changes',
        'actions' => 'Actions',
        'edit' => 'Edit',
        'delete' => 'Delete',
        'view' => 'View',
        'status' => 'Status',
        'photo' => 'Photo',
        'avatar' => 'Avatar',
        'details' => 'Details',
        'print_report' => 'Print Report',
        'back_to_list' => 'Back to List',
        'view_schedule' => 'View Schedule',

        // Navigation
        'nav_dashboard' => 'Dashboard',
        'nav_management' => 'Management',
        'nav_customer_list' => 'Customer List',
        'nav_add_customer' => 'Add Customer',
        'nav_user_accounts' => 'User Accounts',
        'nav_reports' => 'Reports',
        'nav_rep_due' => 'Due Collections',
        'nav_rep_overdue' => 'Overdue Payments',
        'nav_rep_pending' => 'Active Loans',
        'nav_rep_paid' => 'Fully Paid Customers',
        'nav_rep_all_cust' => 'All Customers Report',

        // Dashboard
        'dash_hero_desc' => 'Welcome to your Depreciation & Asset Management workspace.',
        'dash_stat_active_cust' => 'Active Customers',
        'dash_stat_registered' => 'Registered in system',
        'dash_stat_view_list' => 'View List',
        'dash_stat_system_users' => 'System Users',
        'dash_stat_auth_staff' => 'Authorized staff',
        'dash_stat_accounts' => 'Accounts',
        'dash_stat_settled' => 'Settled Payments',
        'dash_stat_completed' => 'Completed schedules',
        'dash_stat_report' => 'Report',
        'dash_stat_pending_audit' => 'Pending Audits',
        'dash_stat_sched_status' => 'Schedule status',
        'dash_stat_collections' => 'Collections',
        'dash_quick_actions' => 'Quick Actions & Shortcuts',
        'dash_qa_add_cust' => 'Add Customer',
        'dash_qa_add_cust_desc' => 'Register a new client asset',
        'dash_qa_directory' => 'Customer Directory',
        'dash_qa_directory_desc' => 'View all schedules & loans',
        'dash_qa_due' => 'Due Collections',
        'dash_qa_due_desc' => 'Upcoming payment reports',
        'dash_qa_pending_loans' => 'Pending Loans',
        'dash_qa_pending_loans_desc' => 'Active repayment contracts',
        'dash_qa_paid' => 'Fully Paid',
        'dash_qa_paid_desc' => 'Completed customer files',
        'dash_qa_staff' => 'Staff Access',
        'dash_qa_staff_desc' => 'Admin credentials manager',
        'dash_sys_overview' => 'System Overview',
        'dash_sys_status' => 'System Status',
        'dash_sys_online' => 'Online & Synced',
        'dash_sys_db' => 'Database Engine',
        'dash_sys_ui' => 'UI Architecture',
        'dash_sys_security' => 'Security Level',
        'dash_sys_sec_val' => 'Session Authenticated',
        'dash_protip_title' => 'Pro-tip',
        'dash_protip_desc' => 'Switch between light/dark mode and toggle English/Khmer anytime from the top navigation bar.',

        // Customer
        'cust_title' => 'Customer Directory',
        'cust_subtitle' => 'Manage active client accounts, assets, and depreciation schedules',
        'cust_add_btn' => 'Add New Customer',
        'cust_th_num' => '#',
        'cust_th_photo' => 'Photo',
        'cust_th_name' => 'Customer Name',
        'cust_th_phone' => 'Phone Number',
        'cust_th_product' => 'Product / Asset',
        'cust_th_price' => 'Price',
        'cust_th_actions' => 'Actions',
        'cust_no_records' => 'No customer records found',
        'cust_start_create' => 'Get started by creating your first customer profile.',
        'cust_deleted_success' => 'Customer deleted successfully.',

        // Customer Add / Edit
        'cust_create_title' => 'Create Customer Profile',
        'cust_edit_title' => 'Modify Customer Details',
        'cust_personal_info' => 'Personal Information',
        'cust_full_name' => 'Full Name',
        'cust_phone' => 'Phone Number',
        'cust_id_card' => 'National ID / Passport',
        'cust_address' => 'Physical Address',
        'cust_choose_photo' => 'Customer Photo',
        'cust_photo_hint' => 'JPG, PNG, or WEBP up to 5MB',
        'cust_photo_leave_blank' => 'Leave blank to retain current photo',
        'cust_terms_title' => 'Asset & Loan Terms',
        'cust_product_name' => 'Product / Asset Name',
        'cust_product_price' => 'Product Price ($)',
        'cust_interest' => 'Monthly Interest (%)',
        'cust_duration' => 'Duration (Months)',
        'cust_user_id' => 'Officer / User ID',
        'cust_auto_amort' => 'Automatic Amortization',
        'cust_auto_amort_desc' => 'Once saved, the system will automatically calculate the monthly principal and interest schedule across the specified duration.',
        'cust_note_update' => 'Note on Updating',
        'cust_note_update_desc' => 'Modifying these details will update the customer profile. Existing payment receipts and completed installment entries will remain safely archived.',
        'cust_btn_save_generate' => 'Save & Generate Schedule',

        // Depreciation Schedule
        'depre_title' => 'Depreciation Schedule',
        'depre_asset' => 'Asset',
        'depre_total_paid' => 'Total Paid',
        'depre_repaid' => 'Repaid',
        'depre_cleared_amort' => 'Cleared amortizations',
        'depre_total_pending' => 'Total Outstanding / Pending',
        'depre_remaining' => 'Remaining',
        'depre_remaining_desc' => 'Remaining principal + interest',
        'depre_total_repay' => 'Total Repayment Amount',
        'depre_progress' => 'Repayment Progress',
        'depre_install_sched' => 'Installment Schedule',
        'depre_total_installments' => 'Total Installments',
        'depre_th_due_date' => 'Due Date',
        'depre_th_principal' => 'Principal',
        'depre_th_interest' => 'Monthly Interest',
        'depre_th_total_due' => 'Total Due',
        'depre_th_status' => 'Payment Status',
        'depre_th_action' => 'Action',
        'depre_paid' => 'Paid',
        'depre_pending' => 'Pending',
        'depre_settle_btn' => 'Settle Payment',
        'depre_completed' => 'Completed',
        'depre_no_sched' => 'No amortization schedule found',
        'depre_confirm_settle' => 'Confirm settlement for this monthly installment?',

        // Users
        'user_title' => 'User Accounts',
        'user_subtitle' => 'Manage system administrators and staff credentials',
        'user_add_btn' => 'Add New User',
        'user_th_avatar' => 'Avatar',
        'user_th_username' => 'Username',
        'user_th_userid' => 'User ID',
        'user_th_security' => 'Password Security',
        'user_create_title' => 'Create User Account',
        'user_edit_title' => 'Edit User Account',
        'user_credentials' => 'Account Credentials',
        'user_username' => 'Username',
        'user_password' => 'Password',
        'user_avatar' => 'Profile Avatar',
        'user_avatar_hint' => 'Recommended: Square format JPG/PNG',
        'user_create_btn' => 'Create User',

        // Reports
        'rep1_title' => 'របាយការណ៍ទារលុយ (Upcoming Due Collections)',
        'rep1_subtitle' => 'Customers with payments due within the next 14 days',
        'rep1_due_date' => 'Payment Due Date',
        'rep1_due_amount' => 'Total Due Amount',
        'rep1_empty' => 'No upcoming payments due in the next 14 days',
        'rep1_total_records' => 'Total Due Records',
        'rep1_grand_total' => 'Grand Total Receivable',

        'rep2_title' => 'របាយការណ៍អ្នកអត់បង់ (Overdue / Late Payments)',
        'rep2_subtitle' => 'Customers with unpaid installments past their due date',
        'rep2_due_date' => 'Original Due Date',
        'rep2_overdue_amount' => 'Overdue Amount',
        'rep2_empty' => 'Great news! There are currently no overdue payments.',
        'rep2_total_records' => 'Total Overdue Records',
        'rep2_total_debt' => 'Total Overdue Debt',

        'rep3_title' => 'របាយការណ៍អតិថិជនមិនទាន់បង់ដាច់ (Active Loans)',
        'rep3_subtitle' => 'Customers with active depreciation amortizations yet to be completed',
        'rep3_empty' => 'No active pending customers found',
        'rep3_total' => 'Total Customers with Outstanding Amortizations',

        'rep4_title' => 'របាយការណ៍អតិថិជនបានបង់ដាច់ (Fully Settled Customers)',
        'rep4_subtitle' => 'Customers who have completed all repayment cycles with zero outstanding balance',
        'rep4_empty' => 'No completed accounts recorded yet',
        'rep4_total' => 'Total Completed Customers',
        'rep4_status_settled' => 'Fully Settled',

        'repcus_title' => 'របាយការណ៍អតិថិជន (All Customers Audit)',
        'repcus_subtitle' => 'Master database report of all registered clients',
        'repcus_empty' => 'No customers found',
        'repcus_total' => 'Total Registered Customers',

        // Login
        'login_title' => 'Sign In - Depreciation System',
        'login_heading' => 'Sign In',
        'login_subheading' => 'Depreciation & Asset Management',
        'login_remember' => 'Remember session',
        'login_forgot' => 'Forgot password?',
        'login_btn' => 'Sign In to Dashboard',
        'login_secure' => 'Secure Authentication • AES-Encrypted',
        'login_invalid' => 'Invalid username or password. Please try again.',

        // SweetAlert
        'swal_confirm_title' => 'Are you sure?',
        'swal_confirm_delete_text' => 'This action cannot be undone!',
        'swal_yes_delete' => 'Yes, delete it!',
        'swal_cancel' => 'Cancel',
        'swal_logout_title' => 'Logout Confirmation',
        'swal_logout_text' => 'You will be logged out of your session.',
        'swal_yes_logout' => 'Yes, Log Out',
    ],

    'kh' => [
        // Brand & General
        'app_name' => 'ប្រព័ន្ធរំលស់',
        'app_subtitle' => 'ការគ្រប់គ្រងការរំលស់ និងទ្រព្យសកម្ម',
        'lang_en' => 'English',
        'lang_kh' => 'ភាសាខ្មែរ (Khmer)',
        'language' => 'ភាសា',
        'theme' => 'រចនាប័ទ្ម',
        'search_placeholder' => 'ស្វែងរកទិន្នន័យ...',
        'admin' => 'អ្នកគ្រប់គ្រង',
        'account_settings' => 'ការកំណត់គណនី',
        'sign_out' => 'ចាកចេញពីប្រព័ន្ធ',
        'welcome' => 'សូមស្វាគមន៍',
        'today' => 'ថ្ងៃនេះ',
        'good_morning' => 'អរុណសួស្តី',
        'good_afternoon' => 'ទិវាសួស្តី',
        'good_evening' => 'សាយណ្ហសួស្តី',
        'cancel' => 'បោះបង់',
        'save' => 'រក្សាទុក',
        'save_changes' => 'រក្សាទុកការកែប្រែ',
        'actions' => 'សកម្មភាព',
        'edit' => 'កែប្រែ',
        'delete' => 'លុប',
        'view' => 'មើល',
        'status' => 'ស្ថានភាព',
        'photo' => 'រូបថត',
        'avatar' => 'រូបតំណាង',
        'details' => 'ព័ត៌មានលម្អិត',
        'print_report' => 'បោះពុម្ពរបាយការណ៍',
        'back_to_list' => 'ត្រឡប់ទៅបញ្ជី',
        'view_schedule' => 'មើលតារាងរំលស់',

        // Navigation
        'nav_dashboard' => 'ផ្ទាំងព័ត៌មាន',
        'nav_management' => 'ការគ្រប់គ្រង',
        'nav_customer_list' => 'បញ្ជីអតិថិជន',
        'nav_add_customer' => 'បន្ថែមអតិថិជន',
        'nav_user_accounts' => 'គណនីអ្នកប្រើប្រាស់',
        'nav_reports' => 'របាយការណ៍',
        'nav_rep_due' => 'របាយការណ៍ទារលុយ',
        'nav_rep_overdue' => 'របាយការណ៍អ្នកអត់បង់',
        'nav_rep_pending' => 'អតិថិជនមិនទាន់បង់ដាច់',
        'nav_rep_paid' => 'អតិថិជនបង់ដាច់',
        'nav_rep_all_cust' => 'របាយការណ៍អតិថិជនសរុប',

        // Dashboard
        'dash_hero_desc' => 'សូមស្វាគមន៍មកកាន់ប្រព័ន្ធគ្រប់គ្រងការរំលស់ប្រាក់ និងទ្រព្យសកម្ម។',
        'dash_stat_active_cust' => 'អតិថិជនសកម្ម',
        'dash_stat_registered' => 'បានចុះឈ្មោះក្នុងប្រព័ន្ធ',
        'dash_stat_view_list' => 'មើលបញ្ជី',
        'dash_stat_system_users' => 'អ្នកប្រើប្រាស់ប្រព័ន្ធ',
        'dash_stat_auth_staff' => 'បុគ្គលិកមានសិទ្ធិ',
        'dash_stat_accounts' => 'គណនី',
        'dash_stat_settled' => 'ការបង់ប្រាក់រួចរាល់',
        'dash_stat_completed' => 'តារាងបានបញ្ចប់',
        'dash_stat_report' => 'របាយការណ៍',
        'dash_stat_pending_audit' => 'ការទារប្រាក់រង់ចាំ',
        'dash_stat_sched_status' => 'ស្ថានភាពតារាង',
        'dash_stat_collections' => 'ការប្រមូលប្រាក់',
        'dash_quick_actions' => 'សកម្មភាពរហ័ស & ផ្លូវកាត់',
        'dash_qa_add_cust' => 'បន្ថែមអតិថិជន',
        'dash_qa_add_cust_desc' => 'ចុះឈ្មោះទ្រព្យសកម្មអតិថិជនថ្មី',
        'dash_qa_directory' => 'បញ្ជីអតិថិជន',
        'dash_qa_directory_desc' => 'មើលតារាងបង់ប្រាក់ និងកម្ចីទាំងអស់',
        'dash_qa_due' => 'របាយការណ៍ទារលុយ',
        'dash_qa_due_desc' => 'របាយការណ៍បង់ប្រាក់ជិតមកដល់',
        'dash_qa_pending_loans' => 'កម្ចីមិនទាន់ដាច់',
        'dash_qa_pending_loans_desc' => 'កិច្ចសន្យារំលស់កំពុងដំណើរការ',
        'dash_qa_paid' => 'បានបង់ដាច់',
        'dash_qa_paid_desc' => 'ឯកសារអតិថិជនបានបង់ដាច់សរុប',
        'dash_qa_staff' => 'គណនីបុគ្គលិក',
        'dash_qa_staff_desc' => 'គ្រប់គ្រងសិទ្ធិអ្នកប្រើប្រាស់',
        'dash_sys_overview' => 'ទិដ្ឋភាពទូទៅនៃប្រព័ន្ធ',
        'dash_sys_status' => 'ស្ថានភាពប្រព័ន្ធ',
        'dash_sys_online' => 'អនឡាញ & ធ្វើសមកាលកម្ម',
        'dash_sys_db' => 'ម៉ាស៊ីនមូលដ្ឋានទិន្នន័យ',
        'dash_sys_ui' => 'រចនាសម្ព័ន្ធផ្ទៃកម្មវិធី',
        'dash_sys_security' => 'កម្រិតសុវត្ថិភាព',
        'dash_sys_sec_val' => 'បានផ្ទៀងផ្ទាត់សម័យ',
        'dash_protip_title' => 'គន្លឹះមានប្រយោជន៍',
        'dash_protip_desc' => 'អ្នកអាចប្តូររវាងពន្លឺ/ងងឹត និងប្តូរភាសា អង់គ្លេស/ខ្មែរ បានគ្រប់ពេលពីរបាររុករកខាងលើ។',

        // Customer
        'cust_title' => 'បញ្ជីអតិថិជន',
        'cust_subtitle' => 'គ្រប់គ្រងគណនីអតិថិជន ទ្រព្យសកម្ម និងតារាងរំលស់ប្រាក់',
        'cust_add_btn' => 'បន្ថែមអតិថិជនថ្មី',
        'cust_th_num' => '#',
        'cust_th_photo' => 'រូបថត',
        'cust_th_name' => 'ឈ្មោះអតិថិជន',
        'cust_th_phone' => 'លេខទូរស័ព្ទ',
        'cust_th_product' => 'ផលិតផល / ទ្រព្យសកម្ម',
        'cust_th_price' => 'តម្លៃ',
        'cust_th_actions' => 'សកម្មភាព',
        'cust_no_records' => 'រកមិនឃើញទិន្នន័យអតិថិជនឡើយ',
        'cust_start_create' => 'ចាប់ផ្តើមដោយការបង្កើតប្រវត្តិរូបអតិថិជនដំបូងរបស់អ្នក។',
        'cust_deleted_success' => 'បានលុបទិន្នន័យអតិថិជនដោយជោគជ័យ។',

        // Customer Add / Edit
        'cust_create_title' => 'បង្កើតប្រវត្តិរូបអតិថិជន',
        'cust_edit_title' => 'កែប្រែព័ត៌មានអតិថិជន',
        'cust_personal_info' => 'ព័ត៌មានផ្ទាល់ខ្លួន',
        'cust_full_name' => 'ឈ្មោះពេញ',
        'cust_phone' => 'លេខទូរស័ព្ទ',
        'cust_id_card' => 'អត្តសញ្ញាណប័ណ្ណ / លិខិតឆ្លងដែន',
        'cust_address' => 'អាសយដ្ឋានបច្ចុប្បន្ន',
        'cust_choose_photo' => 'រូបថតអតិថិជន',
        'cust_photo_hint' => 'JPG, PNG ឬ WEBP មិនលើសពី 5MB',
        'cust_photo_leave_blank' => 'ទុកនៅទទេបើមិនចង់ប្តូររូបថតបច្ចុប្បន្ន',
        'cust_terms_title' => 'លក្ខខណ្ឌទ្រព្យសកម្ម និងកម្ចី',
        'cust_product_name' => 'ឈ្មោះផលិតផល / ទ្រព្យសកម្ម',
        'cust_product_price' => 'តម្លៃផលិតផល ($)',
        'cust_interest' => 'ការប្រាក់ប្រចាំខែ (%)',
        'cust_duration' => 'រយៈពេលបង់ (ខែ)',
        'cust_user_id' => 'លេខសម្គាល់បុគ្គលិក / អ្នកប្រើ',
        'cust_auto_amort' => 'ការគណនារំលស់ដោយស្វ័យប្រវត្តិ',
        'cust_auto_amort_desc' => 'នៅពេលរក្សាទុក ប្រព័ន្ធនឹងបង្កើតតារាងបង់ប្រាក់ដើម និងការប្រាក់ប្រចាំខែដោយស្វ័យប្រវត្តិតាមរយៈពេលដែលបានកំណត់។',
        'cust_note_update' => 'ចំណាំលើការកែប្រែ',
        'cust_note_update_desc' => 'ការកែប្រែព័ត៌មាននេះនឹងធ្វើបច្ចុប្បន្នភាពទិន្នន័យអតិថិជន។ កំណត់ត្រាបង់ប្រាក់កន្លងមកនឹងនៅតែរក្សាទុកដដែល។',
        'cust_btn_save_generate' => 'រក្សាទុក & បង្កើតតារាងរំលស់',

        // Depreciation Schedule
        'depre_title' => 'តារាងរំលស់ប្រាក់',
        'depre_asset' => 'ទ្រព្យសកម្ម',
        'depre_total_paid' => 'បានបង់សរុប',
        'depre_repaid' => 'បានសងរួច',
        'depre_cleared_amort' => 'ការទូទាត់ដែលបានបង់ដាច់',
        'depre_total_pending' => 'ប្រាក់នៅសល់មិនទាន់បង់',
        'depre_remaining' => 'នៅសល់',
        'depre_remaining_desc' => 'ប្រាក់ដើមនៅសល់ + ការប្រាក់',
        'depre_total_repay' => 'ចំនួនទឹកប្រាក់សងសរុប',
        'depre_progress' => 'វឌ្ឍនភាពនៃការសងប្រាក់',
        'depre_install_sched' => 'តារាងបង់ប្រាក់ប្រចាំខែ',
        'depre_total_installments' => 'ចំនួនលើកសរុប',
        'depre_th_due_date' => 'ថ្ងៃត្រូវបង់',
        'depre_th_principal' => 'ប្រាក់ដើម',
        'depre_th_interest' => 'ការប្រាក់ប្រចាំខែ',
        'depre_th_total_due' => 'សរុបត្រូវបង់',
        'depre_th_status' => 'ស្ថានភាពបង់ប្រាក់',
        'depre_th_action' => 'សកម្មភាព',
        'depre_paid' => 'បានបង់រួច',
        'depre_pending' => 'រង់ចាំការបង់',
        'depre_settle_btn' => 'ទូទាត់ប្រាក់',
        'depre_completed' => 'រួចរាល់',
        'depre_no_sched' => 'មិនមានតារាងរំលស់ប្រាក់ឡើយ',
        'depre_confirm_settle' => 'តើអ្នកពិតជាចង់ទូទាត់ការបង់ប្រាក់ប្រចាំខែនេះមែនទេ?',

        // Users
        'user_title' => 'គណនីអ្នកប្រើប្រាស់',
        'user_subtitle' => 'គ្រប់គ្រងអ្នកគ្រប់គ្រងប្រព័ន្ធ និងបុគ្គលិក',
        'user_add_btn' => 'បន្ថែមអ្នកប្រើថ្មី',
        'user_th_avatar' => 'រូបតំណាង',
        'user_th_username' => 'ឈ្មោះអ្នកប្រើ',
        'user_th_userid' => 'លេខសម្គាល់',
        'user_th_security' => 'សុវត្ថិភាពពាក្យសម្ងាត់',
        'user_create_title' => 'បង្កើតគណនីអ្នកប្រើប្រាស់',
        'user_edit_title' => 'កែប្រែគណនីអ្នកប្រើប្រាស់',
        'user_credentials' => 'ព័ត៌មានសម្ងាត់គណនី',
        'user_username' => 'ឈ្មោះអ្នកប្រើ',
        'user_password' => 'ពាក្យសម្ងាត់',
        'user_avatar' => 'រូបតំណាងគណនី',
        'user_avatar_hint' => 'ទម្រង់ការ៉េ JPG/PNG ត្រូវបានណែនាំ',
        'user_create_btn' => 'បង្កើតអ្នកប្រើ',

        // Reports
        'rep1_title' => 'របាយការណ៍ទារលុយ (Upcoming Due Collections)',
        'rep1_subtitle' => 'អតិថិជនដែលមានកាលបរិច្ឆេទត្រូវបង់ប្រាក់ក្នុងរយៈពេល ១៤ ថ្ងៃខាងមុខ',
        'rep1_due_date' => 'ថ្ងៃត្រូវបង់ប្រាក់',
        'rep1_due_amount' => 'ចំនួនទឹកប្រាក់ត្រូវបង់',
        'rep1_empty' => 'មិនមានការបង់ប្រាក់ត្រូវទារក្នុងរយៈពេល ១៤ ថ្ងៃខាងមុខឡើយ',
        'rep1_total_records' => 'ចំនួនកំណត់ត្រាត្រូវទារ',
        'rep1_grand_total' => 'ទឹកប្រាក់ត្រូវប្រមូលសរុប',

        'rep2_title' => 'របាយការណ៍អ្នកអត់បង់ (Overdue / Late Payments)',
        'rep2_subtitle' => 'អតិថិជនដែលហួសកាលកំណត់បង់ប្រាក់',
        'rep2_due_date' => 'កាលបរិច្ឆេទកំណត់ដើម',
        'rep2_overdue_amount' => 'ចំនួនទឹកប្រាក់ហួសកំណត់',
        'rep2_empty' => 'ដំណឹងល្អ! បច្ចុប្បន្នមិនមានអតិថិជនហួសកំណត់បង់ប្រាក់ឡើយ។',
        'rep2_total_records' => 'ចំនួនកំណត់ត្រាហួសកំណត់',
        'rep2_total_debt' => 'បំណុលហួសកំណត់សរុប',

        'rep3_title' => 'របាយការណ៍អតិថិជនមិនទាន់បង់ដាច់ (Active Loans)',
        'rep3_subtitle' => 'អតិថិជនដែលនៅមានកាតព្វកិច្ចបង់ប្រាក់រំលស់កំពុងដំណើរការ',
        'rep3_empty' => 'រកមិនឃើញអតិថិជនកំពុងដំណើរការឡើយ',
        'rep3_total' => 'ចំនួនអតិថិជនមិនទាន់បង់ដាច់សរុប',

        'rep4_title' => 'របាយការណ៍អតិថិជនបានបង់ដាច់ (Fully Settled Customers)',
        'rep4_subtitle' => 'អតិថិជនដែលបានបញ្ចប់ការបង់ប្រាក់រំលស់ទាំងអស់ដោយជោគជ័យ',
        'rep4_empty' => 'មិនទាន់មានគណនីដែលបានបង់ដាច់នៅឡើយទេ',
        'rep4_total' => 'អតិថិជនបានបង់ដាច់សរុប',
        'rep4_status_settled' => 'បានបង់ដាច់រួចរាល់',

        'repcus_title' => 'របាយការណ៍អតិថិជន (All Customers Audit)',
        'repcus_subtitle' => 'របាយការណ៍ទិន្នន័យអតិថិជនដែលបានចុះឈ្មោះទាំងអស់ក្នុងប្រព័ន្ធ',
        'repcus_empty' => 'រកមិនឃើញអតិថិជនឡើយ',
        'repcus_total' => 'អតិថិជនបានចុះឈ្មោះសរុប',

        // Login
        'login_title' => 'ចូលប្រព័ន្ធ - Depreciation System',
        'login_heading' => 'ចូលប្រព័ន្ធ',
        'login_subheading' => 'ការគ្រប់គ្រងការរំលស់ និងទ្រព្យសកម្ម',
        'login_remember' => 'ចងចាំការចូល',
        'login_forgot' => 'ភ្លេចពាក្យសម្ងាត់?',
        'login_btn' => 'ចូលទៅកាន់ផ្ទាំងព័ត៌មាន',
        'login_secure' => 'សុវត្ថិភាពការផ្ទៀងផ្ទាត់ • ការអ៊ិនគ្រីប AES',
        'login_invalid' => 'ឈ្មោះអ្នកប្រើ ឬពាក្យសម្ងាត់មិនត្រឹមត្រូវទេ។ សូមព្យាយាមម្តងទៀត។',

        // SweetAlert
        'swal_confirm_title' => 'តើអ្នកប្រាកដដែរឬទេ?',
        'swal_confirm_delete_text' => 'សកម្មភាពនេះមិនអាចត្រឡប់ក្រោយវិញបានទេ!',
        'swal_yes_delete' => 'បាទ/ចាស លុបចេញ!',
        'swal_cancel' => 'បោះបង់',
        'swal_logout_title' => 'បញ្ជាក់ការចាកចេញ',
        'swal_logout_text' => 'អ្នកនឹងត្រូវចាកចេញពីគណនីរបស់អ្នក។',
        'swal_yes_logout' => 'បាទ/ចាស ចាកចេញ',
    ]
];

// Helper translation function
function __(?string $key, ?string $default = null): string {
    global $translations, $current_lang;
    $safeKey = $key ?? '';
    $lang = (string)($current_lang ?? 'en');
    if (isset($translations[$lang][$safeKey])) {
        return (string)$translations[$lang][$safeKey];
    }
    if (isset($translations['en'][$safeKey])) {
        return (string)$translations['en'][$safeKey];
    }
    return $default !== null ? $default : $safeKey;
}

// Helper to construct language switch URL preserving query parameters
function getLangUrl(string $target_lang): string {
    $params = $_GET;
    $params['lang'] = $target_lang;
    $requestUri = (string)($_SERVER["REQUEST_URI"] ?? '');
    $baseUrl = strtok($requestUri, '?');
    if ($baseUrl === false) {
        $baseUrl = '';
    }
    return htmlspecialchars($baseUrl . '?' . http_build_query($params), ENT_QUOTES, 'UTF-8');
}
