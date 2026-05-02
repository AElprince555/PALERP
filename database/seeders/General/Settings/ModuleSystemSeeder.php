<?php

namespace Database\Seeders\General\Settings;

use App\Models\Module;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ModuleSystemSeeder extends Seeder
{
    /**
     * @throws \Throwable
     */
    public function run(): void
    {
            Schema::disableForeignKeyConstraints();
            Module::truncate();
            Schema::enableForeignKeyConstraints();

            // 1. القطاعات (Level 1)
            $this->seedSectors();

            // 2. الموديولات الفرعية (Level 2)
            $this->seedSubModules();

            // 3. كافة التطبيقات (Level 3) - تم دمج الكل هنا
            $this->seedAllApplications();
    }

    private function seedSectors(): void
    {
        $sectors = [
            'GEN' => [
                'sort' => 1,
                'icon' => 'o-cog-6-tooth',
                'name' => ['ar' => 'الإعدادات العامة', 'en' => 'General Settings'],
                'description' => [
                    'ar' => 'النواة المركزية للنظام، تشمل إدارة المستخدمين، الصلاحيات، والهيكل التنظيمي.',
                    'en' => 'System core: management of users, permissions, and organizational structure.'
                ],
                'route' => 'general', // سيؤدي إلى مسارات مثل general.dashboard
                'metadata' => ['is_core' => true],
                'settings' => ['theme' => 'light']
            ],
            'FIN' => [
                'sort' => 2,
                'icon' => 'o-banknotes',
                'name' => ['ar' => 'الإدارة المالية', 'en' => 'Financial'],
                'description' => [
                    'ar' => 'إدارة الدورة المحاسبية الكاملة، الأستاذ العام، والأصول الثابتة.',
                    'en' => 'Full accounting cycle management, GL, and fixed assets.'
                ],
                'route' => 'finance', // سيؤدي إلى مسارات مثل finance.dashboard
                'metadata' => ['requires_fiscal_year' => true],
                'settings' => ['currency' => 'EGP']
            ],
            'INV' => [
                'sort' => 3,
                'icon' => 'o-archive-box',
                'name' => ['ar' => 'المخازن والمستودعات', 'en' => 'Inventory'],
                'description' => [
                    'ar' => 'مراقبة المخزون اللحظية، إدارة الأصناف، والمستودعات.',
                    'en' => 'Real-time stock control, item management, and warehousing.'
                ],
                'route' => 'inventory',
                'metadata' => ['multi_warehouse' => true],
                'settings' => ['valuation' => 'FIFO']
            ],
            'HR' => [
                'sort' => 4,
                'icon' => 'o-users',
                'name' => ['ar' => 'الموارد البشرية', 'en' => 'Human Resources'],
                'description' => [
                    'ar' => 'إدارة شؤون الموظفين، مسيرات الرواتب والتوظيف.',
                    'en' => 'Personnel management, payroll, and recruitment.'
                ],
                'route' => 'hr',
                'metadata' => ['payroll_integrated' => true],
                'settings' => ['attendance_type' => 'biometric']
            ],
            'PUR' => [
                'sort' => 5,
                'icon' => 'o-shopping-bag',
                'name' => ['ar' => 'المشتريات والموردين', 'en' => 'Purchasing'],
                'description' => [
                    'ar' => 'سلسلة التوريد، طلبات الشراء وفواتير الموردين.',
                    'en' => 'Supply chain, purchase requests, and vendor invoices.'
                ],
                'route' => 'purchasing',
                'metadata' => ['vendor_portal' => true],
                'settings' => ['po_approval' => true]
            ],
            'SAL' => [
                'sort' => 7, // ترتيب المبيعات قبل CRM أو حسب رغبتك
                'icon' => 'o-shopping-cart',
                'name' => ['ar' => 'المبيعات', 'en' => 'Sales'],
                'description' => [
                    'ar' => 'دورة المبيعات، عروض الأسعار، ونقاط البيع POS.',
                    'en' => 'Sales cycle, quotations, and POS management.'
                ],
                'route' => 'sales',
                'metadata' => ['e_invoice' => true],
                'settings' => ['tax_rate' => 14]
            ],
            'CRM' => [
                'sort' => 6,
                'icon' => 'o-presentation-chart-line',
                'name' => ['ar' => 'علاقات العملاء', 'en' => 'CRM'],
                'description' => [
                    'ar' => 'تتبع الفرص البيعية وإدارة العملاء المحتملين.',
                    'en' => 'Sales opportunities tracking and lead management.'
                ],
                'route' => 'crm',
                'metadata' => ['marketing_automation' => false],
                'settings' => ['retention_days' => 90]
            ],
            'PRJ' => [
                'sort' => 8,
                'icon' => 'o-briefcase',
                'name' => ['ar' => 'إدارة المشاريع', 'en' => 'Projects'],
                'description' => [
                    'ar' => 'تخطيط المشاريع وتتبع التكاليف والمهام.',
                    'en' => 'Project planning, cost tracking, and tasks.'
                ],
                'route' => 'projects',
                'metadata' => ['timesheet' => true],
                'settings' => ['billing_method' => 'milestone']
            ],
        ];

        foreach ($sectors as $code => $data) {
            Module::updateOrCreate(
                ['code' => $code],
                [
                    'name'           => $data['name'],
                    'description'    => $data['description'],
                    'type'           => 'sector',
                    'icon'           => $data['icon'],
                    'route'          => $data['route'], // هنا التعديل الجديد
                    'sort_order'     => $data['sort'],
                    'permission_key' => strtolower($code),
                    'metadata'       => $data['metadata'],
                    'settings'       => $data['settings'],
                    'level'          => 1,
                    'is_active'      => true,
                ]
            );
        }
    }
    private function seedSubModules(): void
    {
        // 1. الإعدادات العامة (GEN)
        $this->seedLayer('GEN', [
            [
                'code' => 'GEN-WRD',
                'icon' => 'o-globe-alt',
                'name' => ['ar' => 'العالم والبيانات المركزية', 'en' => 'World & Central Data'],
                'route' => 'general.world',
                'description' => ['ar' => 'إدارة الدول، العملات، المناطق الزمنية، والبيانات الجغرافية العالمية.', 'en' => 'Manage countries, currencies, timezones, and global geographic data.'],
                'metadata' => ['has_api_sync' => true, 'is_static_data' => true],
                'settings' => ['default_country' => 'EG', 'default_currency' => 'EGP']
            ],
            [
                'code' => 'GEN-SET',
                'icon' => 'o-adjustments-horizontal',
                'name' => ['ar' => 'إعدادات النظام', 'en' => 'System Settings'],
                'route' => 'general.settings',
                'description' => ['ar' => 'ضبط إعدادات التشغيل العامة، الترقيم التلقائي، وهيكل النظام.', 'en' => 'Configure general operation settings, auto-numbering, and system structure.'],
                'metadata' => ['critical_config' => true],
                'settings' => ['maintenance_mode' => false, 'timezone' => 'Africa/Cairo']
            ],
            [
                'code' => 'GEN-SEC',
                'icon' => 'o-shield-check',
                'name' => ['ar' => 'الأمان والصلاحيات', 'en' => 'Security & Roles'],
                'route' => 'general.security',
                'description' => ['ar' => 'إدارة المستخدمين، الأدوار، صلاحيات الوصول، وسياسات الحماية.', 'en' => 'Manage users, roles, access permissions, and security policies.'],
                'metadata' => ['high_security' => true],
                'settings' => ['password_expiry_days' => 90, 'two_factor_auth' => false]
            ],
            [
                'code' => 'GEN-AUD',
                'icon' => 'o-magnifying-glass-circle',
                'name' => ['ar' => 'التدقيق والسجلات', 'en' => 'Audit & Logs'],
                'route' => 'general.audit',
                'description' => ['ar' => 'مراقبة سجلات النظام، تتبع التغييرات، وتدقيق العمليات.', 'en' => 'Monitor system logs, track changes, and audit operations.'],
                'metadata' => ['read_only' => true],
                'settings' => ['log_retention_days' => 365]
            ],
            [
                'code' => 'GEN-NOT',
                'icon' => 'o-bell-alert',
                'name' => ['ar' => 'الإشعارات والتنبيهات', 'en' => 'Notifications'],
                'route' => 'general.notifications',
                'description' => ['ar' => 'إدارة قوالب الإشعارات، البريد الإلكتروني، وتنبيهات النظام.', 'en' => 'Manage notification templates, emails, and system alerts.'],
                'metadata' => ['async_process' => true],
                'settings' => ['smtp_enabled' => true, 'sms_gateway' => 'none']
            ],
            [
                'code' => 'GEN-FIL',
                'icon' => 'o-folder-open',
                'name' => ['ar' => 'إدارة الملفات والأرشيف', 'en' => 'File Management'],
                'route' => 'general.files',
                'description' => ['ar' => 'الأرشيف الإلكتروني، إدارة المرفقات، وسعات التخزين.', 'en' => 'E-Archiving, attachment management, and storage capacities.'],
                'metadata' => ['storage_intensive' => true],
                'settings' => ['max_file_size_mb' => 10, 'allowed_extensions' => ['pdf', 'jpg', 'png', 'docx']]
            ],
            [
                'code' => 'GEN-APP',
                'icon' => 'o-squares-plus',
                'name' => ['ar' => 'التطبيقات العامة', 'en' => 'General Apps'],
                'route' => 'general.apps',
                'description' => ['ar' => 'الأدوات المساعدة مثل المفكرة، التقويم، والمراسلات الداخلية.', 'en' => 'Utility tools like Notes, Calendar, and Internal Messaging.'],
                'metadata' => ['collaboration_tools' => true],
                'settings' => ['internal_chat_enabled' => true]
            ],
        ]);

        // 2. الإدارة المالية (FIN)
        $this->seedLayer('FIN', [
            [
                'code' => 'FIN-GL',
                'icon' => 'o-book-open',
                'name' => ['ar' => 'الأستاذ العام', 'en' => 'General Ledger'],
                'route' => 'finance.gl',
                'description' => ['ar' => 'شجرة الحسابات، قيود اليومية، مراكز التكلفة، والتقارير الختامية.', 'en' => 'Chart of accounts, journals, cost centers, and financial statements.'],
                'metadata' => ['requires_fiscal_year' => true, 'is_core' => true],
                'settings' => ['allow_direct_posting' => false, 'max_gl_levels' => 9]
            ],
            [
                'code' => 'FIN-AP',
                'icon' => 'o-arrow-down-tray',
                'name' => ['ar' => 'حسابات الدائنين', 'en' => 'Accounts Payable'],
                'route' => 'finance.ap',
                'description' => ['ar' => 'إدارة الموردين، فواتير المشتريات، ومدفوعات الموردين.', 'en' => 'Manage vendors, purchase invoices, and vendor payments.'],
                'metadata' => ['linked_to_purchasing' => true],
                'settings' => ['auto_generate_payment_vouchers' => false]
            ],
            [
                'code' => 'FIN-AR',
                'icon' => 'o-arrow-up-tray',
                'name' => ['ar' => 'حسابات المدينين', 'en' => 'Accounts Receivable'],
                'route' => 'finance.ar',
                'description' => ['ar' => 'إدارة العملاء، فواتير المبيعات، وتحصيلات العملاء.', 'en' => 'Manage customers, sales invoices, and customer collections.'],
                'metadata' => ['linked_to_sales' => true],
                'settings' => ['credit_limit_check' => true]
            ],
            [
                'code' => 'FIN-BNK',
                'icon' => 'o-building-library',
                'name' => ['ar' => 'البنوك والشيكات', 'en' => 'Banks & Cheques'],
                'route' => 'finance.banks',
                'description' => ['ar' => 'الحسابات البنكية، التسويات، وإدارة الشيكات الصادرة والواردة.', 'en' => 'Bank accounts, reconciliation, and cheque management.'],
                'metadata' => ['banking_security' => true],
                'settings' => ['reconciliation_auto_match' => true]
            ],
            [
                'code' => 'FIN-CSH',
                'icon' => 'o-banknotes',
                'name' => ['ar' => 'الخزائن والنقدية', 'en' => 'Cash Management'],
                'route' => 'finance.cash',
                'description' => ['ar' => 'إدارة صناديق النقدية، المقبوضات، والمدفوعات النقدية المباشرة.', 'en' => 'Manage cash boxes, receipts, and direct cash payments.'],
                'metadata' => ['audit_required' => true],
                'settings' => ['max_petty_cash_limit' => 5000]
            ],
            [
                'code' => 'FIN-FND',
                'icon' => 'o-wallet',
                'name' => ['ar' => 'العهد والتسويات', 'en' => 'Petty Cash & Funds'],
                'route' => 'finance.funds',
                'description' => ['ar' => 'إدارة عهد الموظفين، تسوياتها، والمصاريف النثرية.', 'en' => 'Manage employee petty cash, settlements, and expenses.'],
                'metadata' => ['hr_linked' => true],
                'settings' => ['fund_settlement_grace_days' => 15]
            ],
            [
                'code' => 'FIN-AST',
                'icon' => 'o-building-office',
                'name' => ['ar' => 'الأصول الثابتة', 'en' => 'Fixed Assets'],
                'route' => 'finance.assets',
                'description' => ['ar' => 'سجل الأصول، عمليات الإهلاك، التحويلات، وتخريد الأصول.', 'en' => 'Asset register, depreciation, transfers, and asset disposal.'],
                'metadata' => ['inventory_linked' => true],
                'settings' => ['default_depreciation_method' => 'Straight-Line']
            ],
            [
                'code' => 'FIN-TAX',
                'icon' => 'o-receipt-percent',
                'name' => ['ar' => 'الضرائب والإقرارات', 'en' => 'Taxes & Returns'],
                'route' => 'finance.taxes',
                'description' => ['ar' => 'إعدادات ضريبة القيمة المضافة، الإقرارات الضريبية، والتقارير.', 'en' => 'VAT settings, tax returns, and tax reporting.'],
                'metadata' => ['tax_authority_sync' => false],
                'settings' => ['default_vat_rate' => 14]
            ],
            [
                'code' => 'FIN-LON',
                'icon' => 'o-scale',
                'name' => ['ar' => 'القروض والتسهيلات', 'en' => 'Loans & Facilities'],
                'route' => 'finance.loans',
                'description' => ['ar' => 'إدارة القروض البنكية، عقود التمويل، وجدولة الأقساط.', 'en' => 'Manage bank loans, finance contracts, and schedules.'],
                'metadata' => ['external_financing' => true],
                'settings' => ['interest_calculation_type' => 'Compound']
            ],
            [
                'code' => 'FIN-FCT',
                'icon' => 'o-presentation-chart-line',
                'name' => ['ar' => 'التوقعات المالية', 'en' => 'Financial Forecasting'],
                'route' => 'finance.forecasting',
                'description' => ['ar' => 'تحليل التدفقات النقدية المتوقعة والميزانيات التقديرية.', 'en' => 'Analysis of projected cash flows and estimated budgets.'],
                'metadata' => ['ai_ready' => true],
                'settings' => ['forecasting_horizon_months' => 12]
            ],
            [
                'code' => 'FIN-RPT',
                'icon' => 'o-chart-pie',
                'name' => ['ar' => 'التقارير المالية', 'en' => 'Financial Reports'],
                'route' => 'finance.reports',
                'description' => ['ar' => 'ميزان المراجعة، قائمة الدخل، والميزانية العمومية.', 'en' => 'Trial balance, P&L, and balance sheet reports.'],
                'metadata' => ['pdf_export_ready' => true],
                'settings' => ['report_header_enabled' => true]
            ],
            [
                'code' => 'FIN-BGT',
                'icon' => 'o-calculator',
                'name' => ['ar' => 'الموازنات التقديرية', 'en' => 'Budgets'],
                'route' => 'finance.budgets',
                'description' => ['ar' => 'تخطيط الموازنات السنوية والرقابة على الصرف الفعلي.', 'en' => 'Annual budget planning and actual spend control.'],
                'metadata' => ['approval_workflow' => true],
                'settings' => ['block_posting_on_budget_exceed' => true]
            ],
        ]);

        // 3. المخازن والمستودعات (INV)
        $this->seedLayer('INV', [
            [
                'code' => 'INV-MST',
                'icon' => 'o-cube',
                'name' => ['ar' => 'البيانات الأساسية', 'en' => 'Master Data'],
                'route' => 'inventory.master',
                'description' => ['ar' => 'تعريف الأصناف، الوحدات، المخازن، وفئات المخزون.', 'en' => 'Define items, units, warehouses, and stock categories.'],
                'metadata' => ['barcode_integration' => true],
                'settings' => ['allow_duplicate_item_names' => false]
            ],
            [
                'code' => 'INV-OPS',
                'icon' => 'o-arrows-right-left',
                'name' => ['ar' => 'حركات المخازن', 'en' => 'Inventory Operations'],
                'route' => 'inventory.operations',
                'description' => ['ar' => 'أوامر الاستلام، الصرف، التحويل، ومرتجع المخازن.', 'en' => 'Receipts, issues, transfers, and warehouse returns.'],
                'metadata' => ['realtime_stock' => true],
                'settings' => ['allow_negative_stock' => false]
            ],
            [
                'code' => 'INV-STK',
                'icon' => 'o-clipboard-document-list',
                'name' => ['ar' => 'الجرد والتسويات', 'en' => 'Stock Taking'],
                'route' => 'inventory.audit',
                'description' => ['ar' => 'إدارة جرد المخازن الفعلي ومعالجة فروقات الجرد.', 'en' => 'Physical stock audit and processing variances.'],
                'metadata' => ['blind_audit_enabled' => true],
                'settings' => ['audit_tolerance_percentage' => 2.0]
            ],
            [
                'code' => 'INV-VAL',
                'icon' => 'o-currency-dollar',
                'name' => ['ar' => 'التقييم والتكاليف', 'en' => 'Valuation & Costing'],
                'route' => 'inventory.valuation',
                'description' => ['ar' => 'حساب تكاليف المخزون، طرق التقييم، وتكلفة البضاعة.', 'en' => 'Calculate stock costs, valuation methods, and COGS.'],
                'metadata' => ['accounting_sync' => true],
                'settings' => ['default_valuation_method' => 'FIFO']
            ],
        ]);

        // 4. الموارد البشرية (HR)
        $this->seedLayer('HR', [
            [
                'code' => 'HR-ORG',
                'icon' => 'o-share',
                'name' => ['ar' => 'الهيكل التنظيمي', 'en' => 'Organization'],
                'route' => 'hr.org',
                'description' => ['ar' => 'إدارة الفروع، الأقسام، المسميات الوظيفية، والدرجات.', 'en' => 'Manage branches, departments, job titles, and grades.'],
                'metadata' => ['org_chart_enabled' => true],
                'settings' => ['max_org_levels' => 5]
            ],
            [
                'code' => 'HR-EMP',
                'icon' => 'o-users',
                'name' => ['ar' => 'شؤون الموظفين', 'en' => 'Personnel'],
                'route' => 'hr.employees',
                'description' => ['ar' => 'سجلات الموظفين الشاملة، العقود، والأرشيف الرقمي.', 'en' => 'Comprehensive employee records, contracts, and e-archive.'],
                'metadata' => ['attachment_required' => true],
                'settings' => ['employee_id_auto_gen' => true]
            ],
            [
                'code' => 'HR-ATT',
                'icon' => 'o-clock',
                'name' => ['ar' => 'الحضور والانصراف', 'en' => 'Attendance'],
                'route' => 'hr.attendance',
                'description' => ['ar' => 'جداول الورديات، سياسات الحضور، وسجلات البصمة.', 'en' => 'Shift schedules, attendance policies, and biometric logs.'],
                'metadata' => ['biometric_ready' => true],
                'settings' => ['grace_period_minutes' => 15]
            ],
            [
                'code' => 'HR-PAY',
                'icon' => 'o-calculator',
                'name' => ['ar' => 'الرواتب والأجور', 'en' => 'Payroll'],
                'route' => 'hr.payroll',
                'description' => ['ar' => 'احتساب الرواتب، المكافآت، الاستقطاعات، والسلف.', 'en' => 'Payroll processing, bonuses, deductions, and loans.'],
                'metadata' => ['accounting_linked' => true],
                'settings' => ['salary_payment_day' => 28]
            ],
            [
                'code' => 'HR-REC',
                'icon' => 'o-user-plus',
                'name' => ['ar' => 'التوظيف', 'en' => 'Recruitment'],
                'route' => 'hr.recruitment',
                'description' => ['ar' => 'طلبات الاحتياج، المقابلات، وعروض العمل للمتقدمين.', 'en' => 'Manpower requisitions, interviews, and job offers.'],
                'metadata' => ['hiring_workflow' => true],
                'settings' => ['candidate_expiry_days' => 180]
            ],
            [
                'code' => 'HR-PER',
                'icon' => 'o-star',
                'name' => ['ar' => 'تقييم الأداء', 'en' => 'Performance'],
                'route' => 'hr.performance',
                'description' => ['ar' => 'مؤشرات الأداء KPI، التقييم السنوي، وخطط التطوير.', 'en' => 'KPI indicators, annual appraisal, and development plans.'],
                'metadata' => ['multi_rater_feedback' => false],
                'settings' => ['default_appraisal_scale' => 5]
            ],
            [
                'code' => 'HR-ESS',
                'icon' => 'o-device-phone-mobile',
                'name' => ['ar' => 'الخدمة الذاتية', 'en' => 'Self Service'],
                'route' => 'hr.portal',
                'description' => ['ar' => 'بوابة الموظف لطلب الإجازات، السلف، وعرض قسائم الراتب.', 'en' => 'Employee portal for leaves, loans, and payslips.'],
                'metadata' => ['mobile_ready' => true],
                'settings' => ['allow_payslip_download' => true]
            ],
        ]);

        // 5. المشتريات والموردين (PUR)
        $this->seedLayer('PUR', [
            [
                'code' => 'PUR-VND',
                'icon' => 'o-users',
                'name' => ['ar' => 'سجل الموردين', 'en' => 'Vendor Master'],
                'route' => 'purchasing.vendors',
                'description' => ['ar' => 'إدارة قاعدة بيانات الموردين، التصنيفات، وتقييم الأداء.', 'en' => 'Manage vendor database, categories, and evaluation.'],
                'metadata' => ['has_rating_system' => true],
                'settings' => ['allow_duplicate_tax_ids' => false, 'vendor_auto_code' => true]
            ],
            [
                'code' => 'PUR-CON',
                'icon' => 'o-document-text',
                'name' => ['ar' => 'العقود والاتفاقيات', 'en' => 'Contracts'],
                'route' => 'purchasing.contracts',
                'description' => ['ar' => 'إدارة عقود التوريد طويلة الأجل وأسعار الاتفاقيات.', 'en' => 'Manage supply contracts and price agreements.'],
                'metadata' => ['legal_document_required' => true],
                'settings' => ['contract_expiry_alert_days' => 30]
            ],
            [
                'code' => 'PUR-REQ',
                'icon' => 'o-document-arrow-up',
                'name' => ['ar' => 'طلبات الاحتياج', 'en' => 'Requisitions'],
                'route' => 'purchasing.requisitions',
                'description' => ['ar' => 'إدارة طلبات الشراء الداخلية ودورة الاعتمادات الإدارية.', 'en' => 'Internal purchase requests and approval workflows.'],
                'metadata' => ['workflow_enabled' => true],
                'settings' => ['budget_check_on_request' => true]
            ],
            [
                'code' => 'PUR-RFQ',
                'icon' => 'o-swatch',
                'name' => ['ar' => 'عروض الأسعار', 'en' => 'RFQs'],
                'route' => 'purchasing.rfq',
                'description' => ['ar' => 'إرسال طلبات التسعير للموردين ومقارنة العروض.', 'en' => 'Send RFQs to vendors and compare quotations.'],
                'metadata' => ['multi_vendor_comparison' => true],
                'settings' => ['min_vendors_count' => 3]
            ],
            [
                'code' => 'PUR-ORD',
                'icon' => 'o-shopping-bag',
                'name' => ['ar' => 'أوامر الشراء', 'en' => 'Purchase Orders'],
                'route' => 'purchasing.orders',
                'description' => ['ar' => 'إصدار أوامر الشراء الرسمية ومتابعة حالات التوريد.', 'en' => 'Official purchase orders and delivery tracking.'],
                'metadata' => ['inventory_linked' => true],
                'settings' => ['max_po_amount_no_approval' => 10000]
            ],
            [
                'code' => 'PUR-INV',
                'icon' => 'o-document-duplicate',
                'name' => ['ar' => 'فواتير المشتريات', 'en' => 'Purchase Invoices'],
                'route' => 'purchasing.invoices',
                'description' => ['ar' => 'مطابقة الفواتير مع أوامر الشراء والاستلام المخزني.', 'en' => 'Match invoices with POs and warehouse receipts.'],
                'metadata' => ['accounting_sync' => true],
                'settings' => ['three_way_match_required' => true]
            ],
            [
                'code' => 'PUR-PAY',
                'icon' => 'o-credit-card',
                'name' => ['ar' => 'المدفوعات', 'en' => 'Payments'],
                'route' => 'purchasing.payments',
                'description' => ['ar' => 'إدارة استحقاقات الموردين، الدفعات المقدمة، والتسويات.', 'en' => 'Manage vendor payables, advances, and settlements.'],
                'metadata' => ['bank_integration' => true],
                'settings' => ['payment_priority_levels' => ['high', 'normal', 'low']]
            ],
            [
                'code' => 'PUR-RET',
                'icon' => 'o-arrow-uturn-left',
                'name' => ['ar' => 'المرتجعات', 'en' => 'Returns'],
                'route' => 'purchasing.returns',
                'description' => ['ar' => 'إدارة مرتجعات المشتريات والإشعارات المدينة.', 'en' => 'Purchase returns and debit notes management.'],
                'metadata' => ['inventory_rollback' => true],
                'settings' => ['return_reason_required' => true]
            ],
        ]);

        // 6. المبيعات (SAL)
        $this->seedLayer('SAL', [
            [
                'code' => 'SAL-CON',
                'icon' => 'o-document-text',
                'name' => ['ar' => 'عقود البيع', 'en' => 'Sales Contracts'],
                'route' => 'sales.contracts',
                'description' => ['ar' => 'اتفاقيات البيع الإطارية وقوائم أسعار العملاء.', 'en' => 'Sales framework agreements and customer price lists.'],
                'metadata' => ['fixed_price_lock' => true],
                'settings' => ['allow_over_qty_delivery' => false]
            ],
            [
                'code' => 'SAL-QUO',
                'icon' => 'o-document-plus',
                'name' => ['ar' => 'عروض الأسعار', 'en' => 'Quotations'],
                'route' => 'sales.quotations',
                'description' => ['ar' => 'إرسال العروض الفنية والمالية للعملاء ومتابعتها.', 'en' => 'Send and track customer financial/technical quotes.'],
                'metadata' => ['expiry_logic' => true],
                'settings' => ['default_quote_validity_days' => 7]
            ],
            [
                'code' => 'SAL-ORD',
                'icon' => 'o-shopping-cart',
                'name' => ['ar' => 'أوامر البيع', 'en' => 'Sales Orders'],
                'route' => 'sales.orders',
                'description' => ['ar' => 'تحويل العروض لأوامر تنفيذ وحجز المخزون.', 'en' => 'Convert quotes to orders and stock reservation.'],
                'metadata' => ['stock_reservation' => true],
                'settings' => ['auto_check_credit_limit' => true]
            ],
            [
                'code' => 'SAL-INV',
                'icon' => 'o-document-duplicate',
                'name' => ['ar' => 'فواتير المبيعات', 'en' => 'Sales Invoices'],
                'route' => 'sales.invoices',
                'description' => ['ar' => 'إصدار فواتير البيع النهائية والربط مع الضريبة.', 'en' => 'Issue final sales invoices and tax integration.'],
                'metadata' => ['zatca_integrated' => true],
                'settings' => ['tax_rate' => 14]
            ],
            [
                'code' => 'SAL-COL',
                'icon' => 'o-banknotes',
                'name' => ['ar' => 'التحصيلات', 'en' => 'Collections'],
                'route' => 'sales.collections',
                'description' => ['ar' => 'إدارة مديونيات العملاء، التحصيلات، وأعمار الديون.', 'en' => 'Manage customer debts, collections, and aging.'],
                'metadata' => ['ar_aging_enabled' => true],
                'settings' => ['auto_send_statement' => false]
            ],
            [
                'code' => 'SAL-POS',
                'icon' => 'o-computer-desktop',
                'name' => ['ar' => 'نقاط البيع', 'en' => 'POS'],
                'route' => 'sales.pos',
                'description' => ['ar' => 'واجهة البيع المباشر السريع، الفروع، وإغلاق الورديات.', 'en' => 'Quick POS interface, branches, and session closure.'],
                'metadata' => ['offline_support' => false],
                'settings' => ['auto_print_receipt' => true]
            ],
        ]);

        // 7. إدارة علاقات العملاء (CRM)
        $this->seedLayer('CRM', [
            [
                'code' => 'CRM-LD',
                'icon' => 'o-user-plus',
                'name' => ['ar' => 'العملاء المحتملين', 'en' => 'Leads'],
                'route' => 'crm.leads',
                'description' => ['ar' => 'إدارة المهتمين الجدد وتحويلهم لفرص بيعية.', 'en' => 'Manage new leads and convert them to opportunities.'],
                'metadata' => ['lead_scoring_enabled' => true],
                'settings' => ['lead_assignment_method' => 'round_robin']
            ],
            [
                'code' => 'CRM-OPP',
                'icon' => 'o-chart-pie',
                'name' => ['ar' => 'الفرص البيعية', 'en' => 'Opportunities'],
                'route' => 'crm.opportunities',
                'description' => ['ar' => 'إدارة خط المبيعات ومراحل التفاوض مع العملاء.', 'en' => 'Manage sales pipeline and negotiation stages.'],
                'metadata' => ['pipeline_view' => 'kanban'],
                'settings' => ['default_win_probability' => 20]
            ],
            [
                'code' => 'CRM-ACT',
                'icon' => 'o-calendar',
                'name' => ['ar' => 'الأنشطة والمكالمات', 'en' => 'Activities'],
                'route' => 'crm.activities',
                'description' => ['ar' => 'توثيق سجل المكالمات، الاجتماعات، والمهام التسويقية.', 'en' => 'Log calls, meetings, and marketing tasks.'],
                'metadata' => ['calendar_sync' => true],
                'settings' => ['meeting_reminder_minutes' => 30]
            ],
            [
                'code' => 'CRM-CMP',
                'icon' => 'o-megaphone',
                'name' => ['ar' => 'الحملات التسويقية', 'en' => 'Campaigns'],
                'route' => 'crm.campaigns',
                'description' => ['ar' => 'تخطيط وإرسال الحملات عبر البريد والرسائل.', 'en' => 'Plan and send campaigns via email/SMS.'],
                'metadata' => ['marketing_automation' => true],
                'settings' => ['unsubscribe_link_required' => true]
            ],
            [
                'code' => 'CRM-CST',
                'icon' => 'o-users',
                'name' => ['ar' => 'قاعدة العملاء', 'en' => 'Customers'],
                'route' => 'crm.customers',
                'description' => ['ar' => 'قاعدة بيانات العملاء المركزية وتاريخ التعاملات.', 'en' => 'Central customer database and interaction history.'],
                'metadata' => ['customer_360_view' => true],
                'settings' => ['auto_tagging_enabled' => true]
            ],
        ]);

        // 8. إدارة المشاريع (PRJ)
        $this->seedLayer('PRJ', [
            [
                'code' => 'PRJ-MST',
                'icon' => 'o-building-office-2',
                'name' => ['ar' => 'دليل المشاريع', 'en' => 'Project Master'],
                'route' => 'projects.master',
                'description' => ['ar' => 'تعريف المشاريع الرئيسية، الأكواد، ومديري المشاريع.', 'en' => 'Define main projects, codes, and project managers.'],
                'metadata' => ['cost_center_linked' => true],
                'settings' => ['project_code_format' => 'PRJ-YYYY-{ID}']
            ],
            [
                'code' => 'PRJ-PLN',
                'icon' => 'o-chart-bar',
                'name' => ['ar' => 'تخطيط المشاريع', 'en' => 'Planning & WBS'],
                'route' => 'projects.planning',
                'description' => ['ar' => 'هيكل تقسيم العمل، المهام الرئيسية، والمخطط الزمني.', 'en' => 'Work Breakdown Structure (WBS) and timelines.'],
                'metadata' => ['gantt_enabled' => true],
                'settings' => ['default_working_hours' => 8]
            ],
            [
                'code' => 'PRJ-RES',
                'icon' => 'o-truck',
                'name' => ['ar' => 'الموارد والمعدات', 'en' => 'Resources'],
                'route' => 'projects.resources',
                'description' => ['ar' => 'توزيع العمالة والمعدات على مواقع العمل.', 'en' => 'Staff and equipment allocation to work sites.'],
                'metadata' => ['utilization_report' => true],
                'settings' => ['resource_overbooking_allowed' => false]
            ],
            [
                'code' => 'PRJ-CST',
                'icon' => 'o-currency-dollar',
                'name' => ['ar' => 'تكاليف المشاريع', 'en' => 'Project Costs'],
                'route' => 'projects.costs',
                'description' => ['ar' => 'تتبع المصاريف الفعلية مقابل الميزانية المرصودة.', 'en' => 'Track actual expenses vs allocated budget.'],
                'metadata' => ['budget_variance_alert' => true],
                'settings' => ['profit_margin_target' => 20]
            ],
            [
                'code' => 'PRJ-TSK',
                'icon' => 'o-clipboard-document-list',
                'name' => ['ar' => 'المهام والإنجاز', 'en' => 'Tasks & Progress'],
                'route' => 'projects.tasks',
                'description' => ['ar' => 'إدارة المهام اليومية، سجلات الوقت، ونسب الإنجاز.', 'en' => 'Manage daily tasks, timesheets, and progress.'],
                'metadata' => ['timesheet_approval_required' => true],
                'settings' => ['task_dependency_checks' => true]
            ],
            [
                'code' => 'PRJ-DOC',
                'icon' => 'o-document-check',
                'name' => ['ar' => 'المستندات والمخططات', 'en' => 'Documents'],
                'route' => 'projects.documents',
                'description' => ['ar' => 'أرشفة المخططات الهندسية، العقود، ووثائق المشروع.', 'en' => 'Archive drawings, contracts, and project docs.'],
                'metadata' => ['version_control' => true],
                'settings' => ['external_sharing_enabled' => false]
            ],
        ]);
    }
    private function seedAllApplications(): void
    {
        // ==========================================
// قطاع المالية (FIN Applications)
// ==========================================

// الأستاذ العام (FIN-GL)
        $this->seedAppLayer('FIN-GL', [
            ['suffix' => 'GRP', 'icon' => 'rectangle-group', 'name' => ['ar' => 'مجموعات الحسابات', 'en' => 'Account Groups'], 'description' => ['ar' => 'تصنيف وتبويب الحسابات المالية', 'en' => 'Account classification and grouping'], 'route' => 'finance.gl.groups', 'metadata' => ['is_core' => true]],
            ['suffix' => 'ACC', 'icon' => 'list-bullet', 'name' => ['ar' => 'الحسابات', 'en' => 'Accounts'], 'description' => ['ar' => 'إدارة بطاقات الحسابات الفرعية', 'en' => 'Manage sub-accounts cards'], 'route' => 'finance.gl.accounts', 'metadata' => ['allow_import' => true], 'settings' => ['max_depth' => 5]],
            ['suffix' => 'COA', 'icon' => 'bars-3-bottom-left', 'name' => ['ar' => 'دليل الحسابات', 'en' => 'Chart of Accounts'], 'description' => ['ar' => 'العرض الشجري لدليل الحسابات', 'en' => 'Tree view of Chart of Accounts'], 'route' => 'finance.gl.tree', 'metadata' => ['is_tree' => true]],
            ['suffix' => 'BKS', 'icon' => 'book-open', 'name' => ['ar' => 'دفاتر اليومية', 'en' => 'Books Setup'], 'description' => ['ar' => 'إعداد دفاتر اليومية المساعدة', 'en' => 'Setup auxiliary journal books'], 'route' => 'finance.gl.books', 'settings' => ['require_reconciliation' => true]],
            ['suffix' => 'JRN', 'icon' => 'document-text', 'type' => 'mix', 'name' => ['ar' => 'قيود اليومية', 'en' => 'Journal Vouchers'], 'description' => ['ar' => 'إدخال ومراجعة قيود اليومية', 'en' => 'Entry and review of JVs'], 'route' => 'finance.gl.journals', 'metadata' => ['requires_approval' => true], 'settings' => ['allow_manual_entry' => true]],
            ['suffix' => 'AUT', 'icon' => 'arrow-path-rounded-square', 'name' => ['ar' => 'القيود التلقائية', 'en' => 'Automation Review'], 'description' => ['ar' => 'مراجعة القيود المتولدة آلياً', 'en' => 'Review auto-generated entries'], 'route' => 'finance.gl.auto-entries', 'metadata' => ['read_only' => false]],
            ['suffix' => 'AUD', 'icon' => 'magnifying-glass-circle', 'name' => ['ar' => 'التدقيق الداخلي', 'en' => 'Internal Audit'], 'description' => ['ar' => 'أدوات الرقابة والتدقيق المالي', 'en' => 'Financial audit and control tools'], 'route' => 'finance.gl.audit', 'metadata' => ['high_security' => true]],
            ['suffix' => 'CST', 'icon' => 'chart-pie', 'name' => ['ar' => 'مراكز التكلفة', 'en' => 'Cost Centers'], 'description' => ['ar' => 'توزيع المصاريف على مراكز التكلفة', 'en' => 'Allocate expenses to cost centers'], 'route' => 'finance.gl.costs', 'settings' => ['mandatory' => true, 'allocation_method' => 'percentage']],
            ['suffix' => 'SET', 'icon' => 'cog-6-tooth', 'name' => ['ar' => 'إعدادات الأستاذ العام', 'en' => 'GL Settings'], 'description' => ['ar' => 'تهيئة إعدادات المحاسبة العامة', 'en' => 'Configure GL settings'], 'route' => 'finance.gl.settings', 'metadata' => ['config' => true]],
            ['suffix' => 'PRD', 'icon' => 'calendar-days', 'name' => ['ar' => 'الفترات المالية', 'en' => 'Fiscal Periods'], 'description' => ['ar' => 'إدارة السنوات والشهور المالية', 'en' => 'Manage fiscal years and periods'], 'route' => 'finance.gl.periods', 'settings' => ['allow_future_posting' => false, 'closing_date_check' => true]],
        ]);

// حسابات الدائنين (FIN-AP)
        $this->seedAppLayer('FIN-AP', [
            ['suffix' => 'CAT', 'icon' => 'tag', 'name' => ['ar' => 'تصنيفات الموردين', 'en' => 'Vendor Categories'], 'description' => ['ar' => 'تصنيف الموردين مالياً', 'en' => 'Financial classification of vendors'], 'route' => 'finance.ap.categories', 'metadata' => ['is_static' => false]],
            ['suffix' => 'VND', 'icon' => 'users', 'name' => ['ar' => 'بيانات الموردين المالية', 'en' => 'Vendor Finance'], 'description' => ['ar' => 'إدارة حسابات الموردين الائتمانية', 'en' => 'Manage vendor credit accounts'], 'route' => 'finance.ap.vendors', 'settings' => ['auto_numbering' => true]],
            ['suffix' => 'INV', 'icon' => 'document-duplicate', 'name' => ['ar' => 'فواتير الموردين', 'en' => 'Vendor Invoices'], 'description' => ['ar' => 'تسجيل فواتير المشتريات والخدمات', 'en' => 'Record purchase and service invoices'], 'route' => 'finance.ap.invoices', 'metadata' => ['is_taxable' => true], 'settings' => ['match_po' => true]],
            ['suffix' => 'DEB', 'icon' => 'document-minus', 'name' => ['ar' => 'الإشعارات المدينة', 'en' => 'Debit Notes'], 'description' => ['ar' => 'إصدار إشعارات الخصم للموردين', 'en' => 'Issue debit notes to vendors'], 'route' => 'finance.ap.debit-notes', 'metadata' => ['impacts_tax' => true]],
            ['suffix' => 'PAY', 'icon' => 'banknotes', 'name' => ['ar' => 'سداد المدفوعات', 'en' => 'Vendor Payments'], 'description' => ['ar' => 'صرف المدفوعات للموردين', 'en' => 'Process vendor payments'], 'route' => 'finance.ap.payments', 'metadata' => ['requires_check' => true], 'settings' => ['allow_partial_payment' => true]],
            ['suffix' => 'STL', 'icon' => 'scale', 'name' => ['ar' => 'تسوية المستحقات', 'en' => 'Settlements'], 'description' => ['ar' => 'تسوية الفواتير مع المدفوعات', 'en' => 'Settle invoices with payments'], 'route' => 'finance.ap.settlements', 'metadata' => ['is_transactional' => true]],
            ['suffix' => 'AGE', 'icon' => 'clock', 'name' => ['ar' => 'أعمار الديون', 'en' => 'AP Aging'], 'description' => ['ar' => 'تقرير أعمار ديون الموردين', 'en' => 'Vendor aging report'], 'route' => 'finance.ap.aging', 'metadata' => ['is_report' => true], 'settings' => ['intervals' => [30, 60, 90]]],
            ['suffix' => 'RCN', 'icon' => 'clipboard-document-check', 'name' => ['ar' => 'مطابقة الحسابات', 'en' => 'Reconciliation'], 'description' => ['ar' => 'مطابقة كشوف حسابات الموردين', 'en' => 'Reconcile vendor statements'], 'route' => 'finance.ap.reconcile', 'metadata' => ['audit_related' => true]],
            ['suffix' => 'SET', 'icon' => 'cog', 'name' => ['ar' => 'إعدادات الموردين', 'en' => 'AP Settings'], 'description' => ['ar' => 'إعدادات حسابات الدائنين', 'en' => 'Configure AP settings'], 'route' => 'finance.ap.settings', 'metadata' => ['config' => true]],
        ]);

// حسابات المدينين (FIN-AR)
        $this->seedAppLayer('FIN-AR', [
            ['suffix' => 'CAT', 'icon' => 'tag', 'name' => ['ar' => 'تصنيفات العملاء', 'en' => 'Customer Categories'], 'description' => ['ar' => 'تصنيف العملاء ائتمانياً', 'en' => 'Financial classification of customers'], 'route' => 'finance.ar.categories', 'settings' => ['credit_score_integration' => false]],
            ['suffix' => 'CST', 'icon' => 'user-group', 'name' => ['ar' => 'بيانات العملاء المالية', 'en' => 'Customer Finance'], 'description' => ['ar' => 'إدارة الحدود الائتمانية للعملاء', 'en' => 'Manage customer credit limits'], 'route' => 'finance.ar.customers', 'settings' => ['credit_limit_strict' => true]],
            ['suffix' => 'INV', 'icon' => 'receipt-percent', 'name' => ['ar' => 'فواتير العملاء', 'en' => 'Customer Invoices'], 'description' => ['ar' => 'إصدار فواتير المبيعات والخدمات', 'en' => 'Issue sales and service invoices'], 'route' => 'finance.ar.invoices', 'metadata' => ['e_invoice' => true], 'settings' => ['tax_rate' => 15]],
            ['suffix' => 'CRD', 'icon' => 'document-plus', 'name' => ['ar' => 'الإشعارات الدائنة', 'en' => 'Credit Notes'], 'description' => ['ar' => 'إصدار إشعارات الإضافة للعملاء', 'en' => 'Issue credit notes to customers'], 'route' => 'finance.ar.credit-notes', 'metadata' => ['zatca_linked' => true]],
            ['suffix' => 'COL', 'icon' => 'currency-dollar', 'name' => ['ar' => 'تحصيل المقبوضات', 'en' => 'Customer Receipts'], 'description' => ['ar' => 'سندات قبض العملاء', 'en' => 'Process customer receipts'], 'route' => 'finance.ar.receipts', 'settings' => ['auto_reconciliation' => true]],
            ['suffix' => 'STL', 'icon' => 'scale', 'name' => ['ar' => 'تسوية التحصيلات', 'en' => 'Settlements'], 'description' => ['ar' => 'تسوية الفواتير مع المقبوضات', 'en' => 'Settle invoices with receipts'], 'route' => 'finance.ar.settlements', 'metadata' => ['is_clearing' => true]],
            ['suffix' => 'AGE', 'icon' => 'clock', 'name' => ['ar' => 'أعمار الديون', 'en' => 'AR Aging'], 'description' => ['ar' => 'تقرير أعمار ديون العملاء', 'en' => 'Customer aging report'], 'route' => 'finance.ar.aging', 'metadata' => ['is_report' => true]],
            ['suffix' => 'RCN', 'icon' => 'clipboard-document-check', 'name' => ['ar' => 'مطابقة الحسابات', 'en' => 'Reconciliation'], 'description' => ['ar' => 'مطابقة كشوف حسابات العملاء', 'en' => 'Reconcile customer statements'], 'route' => 'finance.ar.reconcile'],
            ['suffix' => 'SET', 'icon' => 'cog', 'name' => ['ar' => 'إعدادات العملاء', 'en' => 'AR Settings'], 'description' => ['ar' => 'إعدادات حسابات المدينين', 'en' => 'Configure AR settings'], 'route' => 'finance.ar.settings', 'metadata' => ['config' => true]],
        ]);

// البنوك والشيكات (FIN-BNK)
        $this->seedAppLayer('FIN-BNK', [
            ['suffix' => 'ACC', 'icon' => 'building-library', 'name' => ['ar' => 'حسابات البنوك', 'en' => 'Bank Accounts'], 'description' => ['ar' => 'إدارة حسابات الشركة البنكية', 'en' => 'Manage corporate bank accounts'], 'route' => 'finance.banks.accounts', 'metadata' => ['has_iban' => true], 'settings' => ['multi_currency' => true]],
            ['suffix' => 'BKS', 'icon' => 'book-open', 'name' => ['ar' => 'دفاتر الشيكات', 'en' => 'Cheque Books'], 'description' => ['ar' => 'سجلات دفاتر الشيكات المستلمة', 'en' => 'Manage cheque book registries'], 'route' => 'finance.banks.books', 'settings' => ['auto_leaf' => true, 'leaf_count_default' => 25]],
            ['suffix' => 'CHQ', 'icon' => 'ticket', 'name' => ['ar' => 'أوراق القبض والدفع', 'en' => 'Cheques Cycle'], 'description' => ['ar' => 'تتبع دورة حياة الشيكات', 'en' => 'Track cheque lifecycle'], 'route' => 'finance.banks.cheques', 'metadata' => ['workflow' => 'cheque_management']],
            ['suffix' => 'DIR', 'icon' => 'arrows-up-down', 'name' => ['ar' => 'حركات مباشرة', 'en' => 'Direct Transactions'], 'description' => ['ar' => 'الإيداعات والسحوبات المباشرة', 'en' => 'Direct bank deposits and withdrawals'], 'route' => 'finance.banks.direct'],
            ['suffix' => 'TRF', 'icon' => 'arrows-right-left', 'name' => ['ar' => 'التحويلات البنكية', 'en' => 'Bank Transfers'], 'description' => ['ar' => 'التحويل بين الحسابات البنكية', 'en' => 'Inter-bank transfers'], 'route' => 'finance.banks.transfers', 'settings' => ['transfer_fees_account' => null]],
            ['suffix' => 'EXT', 'icon' => 'link', 'name' => ['ar' => 'مراجعة الأنظمة', 'en' => 'Integration Review'], 'description' => ['ar' => 'مراجعة حركات الأنظمة الخارجية', 'en' => 'Review external system transactions'], 'route' => 'finance.banks.integration', 'metadata' => ['api_linked' => true]],
            ['suffix' => 'RCN', 'icon' => 'check-badge', 'name' => ['ar' => 'المطابقة البنكية', 'en' => 'Reconciliation'], 'description' => ['ar' => 'مطابقة كشف البنك مع الدفاتر', 'en' => 'Bank statement reconciliation'], 'route' => 'finance.banks.reconcile', 'settings' => ['auto_match_threshold' => 0.95]],
            ['suffix' => 'SET', 'icon' => 'cog', 'name' => ['ar' => 'إعدادات البنوك', 'en' => 'Bank Settings'], 'description' => ['ar' => 'تهيئة إعدادات المقاصة والبنك', 'en' => 'Configure bank settings'], 'route' => 'finance.banks.settings', 'metadata' => ['config' => true]],
        ]);

// الخزائن والنقدية (FIN-CSH)
        $this->seedAppLayer('FIN-CSH', [
            ['suffix' => 'BOX', 'icon' => 'inbox', 'name' => ['ar' => 'تعريف الخزائن', 'en' => 'Cash Boxes'], 'description' => ['ar' => 'إدارة صناديق النقدية', 'en' => 'Manage cash boxes'], 'route' => 'finance.cash.boxes', 'settings' => ['negative_cash_allowed' => false]],
            ['suffix' => 'MOV', 'icon' => 'banknotes', 'name' => ['ar' => 'حركات مباشرة', 'en' => 'Direct Movements'], 'description' => ['ar' => 'حركات قبض وصرف نقدية', 'en' => 'Cash receipts and payments'], 'route' => 'finance.cash.movements', 'metadata' => ['is_transactional' => true]],
            ['suffix' => 'TRF', 'icon' => 'arrows-right-left', 'name' => ['ar' => 'التحويلات', 'en' => 'Transfers'], 'description' => ['ar' => 'التحويل بين الخزائن والبنوك', 'en' => 'Transfers between boxes and banks'], 'route' => 'finance.cash.transfers'],
            ['suffix' => 'JRD', 'icon' => 'calculator', 'name' => ['ar' => 'محاضر الجرد', 'en' => 'Physical Audit'], 'description' => ['ar' => 'إثبات جرد النقدية الفعلي', 'en' => 'Physical cash audit logs'], 'route' => 'finance.cash.audit', 'metadata' => ['audit' => true], 'settings' => ['blind_audit' => true]],
            ['suffix' => 'EXT', 'icon' => 'link', 'name' => ['ar' => 'مراجعة الأنظمة', 'en' => 'Integration Review'], 'description' => ['ar' => 'مراجعة النقدية من أنظمة البيع', 'en' => 'Review cash from POS systems'], 'route' => 'finance.cash.integration'],
            ['suffix' => 'SET', 'icon' => 'cog', 'name' => ['ar' => 'إعدادات الخزينة', 'en' => 'Cash Settings'], 'description' => ['ar' => 'إعدادات المقبوضات النقدية', 'en' => 'Configure cash settings'], 'route' => 'finance.cash.settings', 'metadata' => ['config' => true]],
        ]);

// العهد والتسويات (FIN-FND)
        $this->seedAppLayer('FIN-FND', [
            ['suffix' => 'REQ', 'icon' => 'document-arrow-up', 'name' => ['ar' => 'طلبات العهد', 'en' => 'Petty Cash Requests'], 'description' => ['ar' => 'طلب وصرف عهدة مالية', 'en' => 'Request and issue petty cash'], 'route' => 'finance.funds.requests', 'metadata' => ['approval_flow' => 'fund_request']],
            ['suffix' => 'STL-DIR', 'icon' => 'check-circle', 'name' => ['ar' => 'تسويات مباشرة', 'en' => 'Direct Settlements'], 'description' => ['ar' => 'تسوية العهد بمستندات يدوية', 'en' => 'Settle funds with manual docs'], 'route' => 'finance.funds.settle-direct'],
            ['suffix' => 'STL-EXT', 'icon' => 'link', 'name' => ['ar' => 'تسويات الأنظمة', 'en' => 'External Settlements'], 'description' => ['ar' => 'تسوية العهد من فواتير النظام', 'en' => 'Settle funds from system invoices'], 'route' => 'finance.funds.settle-external', 'metadata' => ['linked_data' => true]],
            ['suffix' => 'SET', 'icon' => 'cog', 'name' => ['ar' => 'إعدادات العهد', 'en' => 'Fund Settings'], 'description' => ['ar' => 'إعدادات دورة العهد', 'en' => 'Configure fund settings'], 'route' => 'finance.funds.settings', 'settings' => ['max_fund_amount' => 5000]],
        ]);

// الأصول الثابتة (FIN-AST)
        $this->seedAppLayer('FIN-AST', [
            ['suffix' => 'CAT', 'icon' => 'squares-2x2', 'name' => ['ar' => 'مجموعات الأصول', 'en' => 'Asset Categories'], 'description' => ['ar' => 'تصنيف الأصول ونسب الإهلاك', 'en' => 'Categorize assets and dep. rates'], 'route' => 'finance.assets.categories', 'settings' => ['default_dep_rate' => 0.1]],
            ['suffix' => 'REG', 'icon' => 'building-office', 'name' => ['ar' => 'سجل الأصول', 'en' => 'Asset Register'], 'description' => ['ar' => 'إدارة بطاقات الأصول الثابتة', 'en' => 'Manage fixed asset cards'], 'route' => 'finance.assets.register', 'metadata' => ['has_barcode' => true]],
            ['suffix' => 'DEP', 'icon' => 'arrow-trending-down', 'name' => ['ar' => 'الإهلاك الدوري', 'en' => 'Depreciation'], 'description' => ['ar' => 'تشغيل عمليات الإهلاك الشهرية', 'en' => 'Run monthly depreciation'], 'route' => 'finance.assets.depreciation', 'metadata' => ['batch_process' => true]],
            ['suffix' => 'MOV', 'icon' => 'arrows-right-left', 'name' => ['ar' => 'حركات وتحويلات', 'en' => 'Movements'], 'description' => ['ar' => 'نقل عهدة الأصول ومواقعها', 'en' => 'Transfer asset custody and locations'], 'route' => 'finance.assets.movements'],
            ['suffix' => 'MAI', 'icon' => 'wrench', 'name' => ['ar' => 'صيانة وتحسين', 'en' => 'Maintenance'], 'description' => ['ar' => 'إثبات مصاريف صيانة الأصول', 'en' => 'Record asset maintenance costs'], 'route' => 'finance.assets.maintenance'],
            ['suffix' => 'DIS', 'icon' => 'trash', 'name' => ['ar' => 'استبعاد وتخريد', 'en' => 'Disposal'], 'description' => ['ar' => 'بيع أو استبعاد الأصول', 'en' => 'Asset sale or disposal'], 'route' => 'finance.assets.disposal'],
            ['suffix' => 'JRD', 'icon' => 'clipboard-document-check', 'name' => ['ar' => 'جرد الأصول', 'en' => 'Physical Count'], 'description' => ['ar' => 'مطابقة الأصول فعلياً', 'en' => 'Physical asset verification'], 'route' => 'finance.assets.audit'],
            ['suffix' => 'SET', 'icon' => 'cog', 'name' => ['ar' => 'إعدادات الأصول', 'en' => 'Asset Settings'], 'description' => ['ar' => 'إعدادات الربط المحاسبي للأصول', 'en' => 'Configure asset GL links'], 'route' => 'finance.assets.settings', 'metadata' => ['config' => true]],
        ]);

// الضرائب (FIN-TAX)
        $this->seedAppLayer('FIN-TAX', [
            ['suffix' => 'DEF', 'icon' => 'receipt-percent', 'name' => ['ar' => 'أنواع الضرائب', 'en' => 'Tax Definitions'], 'description' => ['ar' => 'تعريف نسب الضرائب والرسوم', 'en' => 'Define tax rates and fees'], 'route' => 'finance.taxes.definitions', 'settings' => ['default_vat' => 15]],
            ['suffix' => 'GRP', 'icon' => 'rectangle-group', 'name' => ['ar' => 'مجموعات الضرائب', 'en' => 'Tax Groups'], 'description' => ['ar' => 'تجميع الضرائب في وعاء واحد', 'en' => 'Grouping taxes for processing'], 'route' => 'finance.taxes.groups'],
            ['suffix' => 'TRN', 'icon' => 'arrows-up-down', 'name' => ['ar' => 'حركات الضرائب', 'en' => 'Tax Transactions'], 'description' => ['ar' => 'مراجعة العمليات الضريبية', 'en' => 'Review tax-related transactions'], 'route' => 'finance.taxes.transactions'],
            ['suffix' => 'PAY', 'icon' => 'banknotes', 'name' => ['ar' => 'سدادات الضرائب', 'en' => 'Tax Payments'], 'description' => ['ar' => 'سداد المستحقات الضريبية', 'en' => 'Process tax payments'], 'route' => 'finance.taxes.payments'],
            ['suffix' => 'RPT', 'icon' => 'document-chart-bar', 'name' => ['ar' => 'الإقرارات الضريبية', 'en' => 'Tax Returns'], 'description' => ['ar' => 'توليد مسودة الإقرار الضريبي', 'en' => 'Generate tax return drafts'], 'route' => 'finance.taxes.returns', 'metadata' => ['zatca_linked' => true]],
            ['suffix' => 'SET', 'icon' => 'cog', 'name' => ['ar' => 'إعدادات الضرائب', 'en' => 'Tax Settings'], 'description' => ['ar' => 'إعدادات الربط الضريبي', 'en' => 'Configure tax integration'], 'route' => 'finance.taxes.settings', 'metadata' => ['config' => true]],
        ]);

// القروض (FIN-LON)
        $this->seedAppLayer('FIN-LON', [
            ['suffix' => 'TYP', 'icon' => 'tag', 'name' => ['ar' => 'أنواع القروض', 'en' => 'Loan Types'], 'description' => ['ar' => 'تصنيف القروض والتسهيلات', 'en' => 'Classify loans and facilities'], 'route' => 'finance.loans.types'],
            ['suffix' => 'SRC', 'icon' => 'building-library', 'name' => ['ar' => 'جهات التمويل', 'en' => 'Financing Sources'], 'description' => ['ar' => 'إدارة البنوك وجهات التمويل', 'en' => 'Manage banks and lenders'], 'route' => 'finance.loans.sources'],
            ['suffix' => 'REG', 'icon' => 'document-text', 'name' => ['ar' => 'سجل القروض', 'en' => 'Loan Register'], 'description' => ['ar' => 'متابعة عقود القروض القائمة', 'en' => 'Follow up on active loan contracts'], 'route' => 'finance.loans.register', 'metadata' => ['calc_method' => 'interest_reducing']],
            ['suffix' => 'INT', 'icon' => 'chart-pie', 'name' => ['ar' => 'الفوائد والعمولات', 'en' => 'Interests'], 'description' => ['ar' => 'احتساب فوائد القروض', 'en' => 'Calculate loan interests'], 'route' => 'finance.loans.interests'],
            ['suffix' => 'SCH', 'icon' => 'calendar-days', 'name' => ['ar' => 'جداول الأقساط', 'en' => 'Schedules'], 'description' => ['ar' => 'متابعة سداد أقساط القروض', 'en' => 'Track loan installment payments'], 'route' => 'finance.loans.schedules', 'settings' => ['reminder_days' => 5]],
            ['suffix' => 'GNT', 'icon' => 'shield-check', 'name' => ['ar' => 'خطابات الضمان', 'en' => 'Letters of Guarantee'], 'description' => ['ar' => 'إدارة الضمانات البنكية', 'en' => 'Manage letters of guarantee'], 'route' => 'finance.loans.guarantees'],
            ['suffix' => 'SET', 'icon' => 'cog', 'name' => ['ar' => 'إعدادات التمويل', 'en' => 'Finance Settings'], 'description' => ['ar' => 'إعدادات حسابات القروض', 'en' => 'Configure loan accounts'], 'route' => 'finance.loans.settings', 'metadata' => ['config' => true]],
        ]);

// التوقعات والتقارير والموازنة (FIN-FCT, FIN-RPT, FIN-BGT)
        $this->seedAppLayer('FIN-FCT', [
            ['suffix' => 'SET', 'icon' => 'cog', 'name' => ['ar' => 'إعدادات التوقعات', 'en' => 'Forecasting Settings'], 'route' => 'finance.forecasting.settings', 'metadata' => ['config' => true]],
            ['suffix' => 'CSH', 'icon' => 'arrow-trending-up', 'name' => ['ar' => 'توقعات الكاش فلو', 'en' => 'Cash Flow Forecast'], 'route' => 'finance.forecasting.cash-flow', 'settings' => ['horizon_months' => 12]],
            ['suffix' => 'REV', 'icon' => 'chart-bar', 'name' => ['ar' => 'توقعات الإيرادات', 'en' => 'Revenue Projection'], 'route' => 'finance.forecasting.revenue'],
            ['suffix' => 'EXP', 'icon' => 'arrow-trending-down', 'name' => ['ar' => 'توقعات المصروفات', 'en' => 'Expense Projection'], 'route' => 'finance.forecasting.expenses'],
            ['suffix' => 'ANL', 'icon' => 'presentation-chart-line', 'name' => ['ar' => 'التحليل المالي', 'en' => 'Financial Analysis'], 'route' => 'finance.forecasting.analysis'],
        ]);

        $this->seedAppLayer('FIN-RPT', [
            ['suffix' => 'SET', 'icon' => 'cog', 'name' => ['ar' => 'إعدادات التقارير', 'en' => 'Reporting Settings'], 'route' => 'finance.reports.settings', 'metadata' => ['config' => true]],
            ['suffix' => 'DEF', 'icon' => 'pencil-square', 'name' => ['ar' => 'تعريفات التقارير', 'en' => 'Report Definitions'], 'route' => 'finance.reports.definitions'],
            ['suffix' => 'CAT', 'icon' => 'folder', 'name' => ['ar' => 'تصنيفات التقارير', 'en' => 'Report Categories'], 'route' => 'finance.reports.categories'],
            ['suffix' => 'AGG', 'icon' => 'circle-stack', 'name' => ['ar' => 'التقارير التجميعية', 'en' => 'Aggregated Movements'], 'route' => 'finance.reports.aggregated'],
            ['suffix' => 'SAV', 'icon' => 'archive-box', 'name' => ['ar' => 'التقارير المحفوظة', 'en' => 'Saved Reports'], 'route' => 'finance.reports.saved'],
        ]);

        $this->seedAppLayer('FIN-BGT', [
            ['suffix' => 'DEF', 'icon' => 'list-bullet', 'name' => ['ar' => 'بنود الموازنة', 'en' => 'Budget Definitions'], 'route' => 'finance.budgets.definitions'],
            ['suffix' => 'PLN', 'icon' => 'chart-pie', 'name' => ['ar' => 'تخطيط الموازنة', 'en' => 'Budget Planning'], 'route' => 'finance.budgets.planning', 'settings' => ['annual_lock' => true]],
            ['suffix' => 'REV', 'icon' => 'pencil-square', 'name' => ['ar' => 'تعديل الموازنة', 'en' => 'Budget Revision'], 'route' => 'finance.budgets.revision'],
            ['suffix' => 'CTL', 'icon' => 'shield-exclamation', 'name' => ['ar' => 'الرقابة على الموازنة', 'en' => 'Budget Control'], 'route' => 'finance.budgets.control', 'settings' => ['strict_blocking' => true]],
            ['suffix' => 'RPT', 'icon' => 'document-chart-bar', 'name' => ['ar' => 'تقارير المقارنات', 'en' => 'Budget Reports'], 'route' => 'finance.budgets.reports'],
            ['suffix' => 'SET', 'icon' => 'cog', 'name' => ['ar' => 'إعدادات الموازنة', 'en' => 'Budget Settings'], 'route' => 'finance.budgets.settings', 'metadata' => ['config' => true]],
        ]);
        // ==========================================
// قطاع المخازن (INV Applications)
// ==========================================

// البيانات الأساسية (INV-MST)
        $this->seedAppLayer('INV-MST', [
            [
                'suffix' => 'CAT', 'icon' => 'rectangle-group',
                'name' => ['ar' => 'مجموعات الأصناف', 'en' => 'Item Categories'],
                'description' => ['ar' => 'تصنيف الأصناف وربطها بالحسابات المالية', 'en' => 'Categorize items and link them to GL accounts'],
                'route' => 'inventory.master.categories',
                'metadata' => ['has_sub_categories' => true, 'is_core' => true],
                'settings' => ['default_tax_group' => null, 'require_gl_account' => true]
            ],
            [
                'suffix' => 'UNT', 'icon' => 'scale',
                'name' => ['ar' => 'وحدات القياس', 'en' => 'Units of Measure'],
                'description' => ['ar' => 'إدارة وحدات القياس ومعاملات التحويل', 'en' => 'Manage UOMs and conversion factors'],
                'route' => 'inventory.master.units',
                'metadata' => ['standard_units' => ['pcs', 'kg', 'm']],
                'settings' => ['allow_decimal_quantities' => true, 'rounding_precision' => 2]
            ],
            [
                'suffix' => 'ITM', 'icon' => 'cube',
                'name' => ['ar' => 'سجل الأصناف', 'en' => 'Item Master'],
                'description' => ['ar' => 'إدارة بطاقات الأصناف والباركود والبيانات الفنية', 'en' => 'Manage item cards, barcodes, and technical data'],
                'route' => 'inventory.master.items',
                'metadata' => ['has_images' => true, 'has_variants' => true, 'track_serial' => true],
                'settings' => ['auto_generate_code' => true, 'barcode_type' => 'EAN-13', 'default_cost_method' => 'WAC']
            ],
            [
                'suffix' => 'LOC', 'icon' => 'building-storefront',
                'name' => ['ar' => 'المستودعات والمواقع', 'en' => 'Locations'],
                'description' => ['ar' => 'تعريف المستودعات وهيكلة المواقع الداخلية (Bin Locations)', 'en' => 'Define warehouses and internal bin locations structure'],
                'route' => 'inventory.master.locations',
                'metadata' => ['is_virtual' => false, 'has_bin_locations' => true],
                'settings' => ['allow_negative_stock' => false, 'enforce_bins' => false]
            ],
            [
                'suffix' => 'SET', 'icon' => 'cog-8-tooth',
                'name' => ['ar' => 'إعدادات البيانات', 'en' => 'Master Settings'],
                'description' => ['ar' => 'تهيئة القواعد العامة لبيانات المخزون', 'en' => 'Configure general inventory master rules'],
                'route' => 'inventory.master.settings',
                'metadata' => ['config' => true],
                'settings' => ['item_code_prefix' => 'ITM-', 'enable_lot_tracking' => true]
            ],
        ]);

// حركات المخازن (INV-OPS)
        $this->seedAppLayer('INV-OPS', [
            [
                'suffix' => 'GRN', 'icon' => 'document-arrow-down', 'type' => 'mix',
                'name' => ['ar' => 'إشعارات الاستلام', 'en' => 'Goods Receipts'],
                'description' => ['ar' => 'إثبات دخول البضائع للمستودعات من الموردين', 'en' => 'Record incoming goods from vendors to warehouses'],
                'route' => 'inventory.operations.receipts',
                'metadata' => ['requires_qc' => true, 'linked_to_po' => true, 'financial_impact' => true],
                'settings' => ['auto_post_to_gl' => true, 'allow_over_receipt' => false]
            ],
            [
                'suffix' => 'ISS', 'icon' => 'document-arrow-up', 'type' => 'mix',
                'name' => ['ar' => 'أوامر الصرف', 'en' => 'Material Issues'],
                'description' => ['ar' => 'صرف المواد للمشاريع أو مراكز التكلفة', 'en' => 'Issue materials to projects or cost centers'],
                'route' => 'inventory.operations.issues',
                'metadata' => ['requires_approval' => true, 'cost_center_required' => true],
                'settings' => ['check_inventory_availability' => true, 'valuation_on_issue' => true]
            ],
            [
                'suffix' => 'TRF', 'icon' => 'arrows-right-left',
                'name' => ['ar' => 'التحويلات المخزنية', 'en' => 'Stock Transfers'],
                'description' => ['ar' => 'نقل الأصناف بين المستودعات والفروع', 'en' => 'Move items between different warehouses and branches'],
                'route' => 'inventory.operations.transfers',
                'metadata' => ['two_step_transfer' => true, 'transit_account_linked' => true],
                'settings' => ['require_receiver_confirmation' => true, 'track_transit_time' => true]
            ],
            [
                'suffix' => 'RET', 'icon' => 'arrow-uturn-left',
                'name' => ['ar' => 'المرتجعات المخزنية', 'en' => 'Stock Returns'],
                'description' => ['ar' => 'إدارة مرتجعات المواد من المواقع للمخازن', 'en' => 'Manage material returns from sites back to warehouse'],
                'route' => 'inventory.operations.returns',
                'metadata' => ['linked_to_issue' => true, 'inspection_required' => true],
                'settings' => ['return_valuation_at_original_cost' => true]
            ],
            [
                'suffix' => 'SET', 'icon' => 'cog-8-tooth',
                'name' => ['ar' => 'إعدادات الحركات', 'en' => 'Operations Settings'],
                'description' => ['ar' => 'إعدادات تسلسل المستندات المخزنية والاعتمادات', 'en' => 'Configure inventory document sequences and approvals'],
                'route' => 'inventory.operations.settings',
                'metadata' => ['config' => true],
                'settings' => ['grn_prefix' => 'GRN-', 'issue_prefix' => 'ISS-']
            ],
        ]);

// الجرد والتسويات (INV-STK)
        $this->seedAppLayer('INV-STK', [
            [
                'suffix' => 'ORD', 'icon' => 'clipboard-document-list',
                'name' => ['ar' => 'أوامر الجرد', 'en' => 'Audit Orders'],
                'description' => ['ar' => 'فتح جلسات جرد دورية أو مفاجئة لمستودع معين', 'en' => 'Open periodic or surprise audit sessions for a warehouse'],
                'route' => 'inventory.audit.orders',
                'metadata' => ['blind_audit' => true, 'supports_mobile_scanning' => true],
                'settings' => ['lock_warehouse_during_audit' => true, 'max_audit_duration_days' => 3]
            ],
            [
                'suffix' => 'TLY', 'icon' => 'pencil-square',
                'name' => ['ar' => 'نتائج الجرد', 'en' => 'Stock Counting'],
                'description' => ['ar' => 'تسجيل الكميات الفعلية ومقارنتها بالرصيد الدفتري', 'en' => 'Record physical quantities and compare with book balance'],
                'route' => 'inventory.audit.counting',
                'metadata' => ['multi_counter_support' => true, 'discrepancy_detection' => true],
                'settings' => ['allow_manual_entry' => true, 'require_witness' => true]
            ],
            [
                'suffix' => 'ADJ', 'icon' => 'adjustments-horizontal',
                'name' => ['ar' => 'التسويات المخزنية', 'en' => 'Stock Adjustments'],
                'description' => ['ar' => 'معالجة فروقات الجرد بالزيادة أو النقصان', 'en' => 'Process inventory variances (Increase/Decrease)'],
                'route' => 'inventory.audit.adjustments',
                'metadata' => ['impacts_gl' => true, 'adjustment_codes' => ['shrinkage', 'damage', 'bonus']],
                'settings' => ['require_manager_approval' => true, 'auto_post_threshold' => 100]
            ],
            [
                'suffix' => 'SET', 'icon' => 'cog-8-tooth',
                'name' => ['ar' => 'إعدادات الجرد', 'en' => 'Audit Settings'],
                'description' => ['ar' => 'إعدادات التسامح (Tolerance) والرقابة على الجرد', 'en' => 'Configure audit tolerance and control rules'],
                'route' => 'inventory.audit.settings',
                'metadata' => ['config' => true],
                'settings' => ['tolerance_percentage' => 2.0, 'recount_threshold' => 5.0]
            ],
        ]);

// التقييم والتكاليف (INV-VAL)
        $this->seedAppLayer('INV-VAL', [
            [
                'suffix' => 'MTH', 'icon' => 'calculator',
                'name' => ['ar' => 'طرق التسعير', 'en' => 'Costing Methods'],
                'description' => ['ar' => 'تحديد طرق التقييم (FIFO, LIFO, WAC)', 'en' => 'Define valuation methods (FIFO, LIFO, WAC)'],
                'route' => 'inventory.valuation.methods',
                'metadata' => ['supports_multiple_methods' => true],
                'settings' => ['default_method' => 'WAC', 'update_cost_on_grn' => true]
            ],
            [
                'suffix' => 'VAL', 'icon' => 'currency-dollar',
                'name' => ['ar' => 'قيمة المخزون', 'en' => 'Valuation Reports'],
                'description' => ['ar' => 'تقارير قيمة المخزون الحالية وتكلفة البضاعة', 'en' => 'Current inventory value and COGS reports'],
                'route' => 'inventory.valuation.reports',
                'metadata' => ['financial_report' => true, 'pivot_support' => true],
                'settings' => ['show_zero_balance' => false, 'valuation_date' => 'current']
            ],
            [
                'suffix' => 'CST', 'icon' => 'banknotes',
                'name' => ['ar' => 'تسوية التكاليف', 'en' => 'Cost Adjustments'],
                'description' => ['ar' => 'تعديل تكلفة الأصناف وتوزيع مصاريف الشحن', 'en' => 'Adjust item costs and distribute landed costs'],
                'route' => 'inventory.valuation.adjustments',
                'metadata' => ['affects_coa' => true, 'supports_landed_costs' => true],
                'settings' => ['allocation_basis' => 'value', 'require_ap_link' => true]
            ],
            [
                'suffix' => 'SET', 'icon' => 'cog-8-tooth',
                'name' => ['ar' => 'إعدادات التقييم', 'en' => 'Valuation Settings'],
                'description' => ['ar' => 'ربط موديول التكاليف مع الأستاذ العام', 'en' => 'Link costing module with General Ledger'],
                'route' => 'inventory.valuation.settings',
                'metadata' => ['config' => true],
                'settings' => ['enable_perpetual_inventory' => true, 'cogs_account_default' => null]
            ],
        ]);
        // ==========================================
// قطاع الموارد البشرية (HR Applications)
// ==========================================

// الهيكل التنظيمي (HR-ORG)
        $this->seedAppLayer('HR-ORG', [
            [
                'suffix' => 'CMP', 'icon' => 'building-office-2',
                'name' => ['ar' => 'الشركات', 'en' => 'Companies'],
                'route' => 'hr.org.companies',
                'description' => ['ar' => 'إدارة بيانات الشركات والكيانات القانونية', 'en' => 'Manage company profiles and legal entities'],
                'metadata' => ['is_core' => true],
                'settings' => ['multi_company_enabled' => true]
            ],
            [
                'suffix' => 'BRN', 'icon' => 'map-pin',
                'name' => ['ar' => 'الفروع', 'en' => 'Branches'],
                'route' => 'hr.org.branches',
                'description' => ['ar' => 'تعريف فروع الشركة ومواقع العمل', 'en' => 'Define company branches and work locations'],
                'metadata' => ['has_geofencing' => true]
            ],
            [
                'suffix' => 'DEP', 'icon' => 'squares-2x2',
                'name' => ['ar' => 'الإدارات والأقسام', 'en' => 'Departments'],
                'route' => 'hr.org.departments',
                'description' => ['ar' => 'بناء الهيكل الإداري للأقسام', 'en' => 'Build administrative department structure'],
                'metadata' => ['hierarchical_view' => true]
            ],
            [
                'suffix' => 'JOB', 'icon' => 'identification',
                'name' => ['ar' => 'المسميات الوظيفية', 'en' => 'Job Titles'],
                'route' => 'hr.org.jobs',
                'description' => ['ar' => 'إدارة المسميات والوصف الوظيفي', 'en' => 'Manage job titles and descriptions'],
                'metadata' => ['linked_to_grades' => true]
            ],
            [
                'suffix' => 'GRD', 'icon' => 'chart-bar',
                'name' => ['ar' => 'الدرجات الوظيفية', 'en' => 'Job Grades'],
                'route' => 'hr.org.grades',
                'description' => ['ar' => 'تعريف مستويات الدرجات والسلالم الوظيفية', 'en' => 'Define job grade levels and scales'],
                'settings' => ['min_salary_control' => true]
            ],
            [
                'suffix' => 'STR', 'icon' => 'share',
                'name' => ['ar' => 'الهيكل التنظيمي', 'en' => 'Org Chart'],
                'route' => 'hr.org.chart',
                'description' => ['ar' => 'عرض شجرة الهيكل التنظيمي للمؤسسة', 'en' => 'Visual organizational structure chart'],
                'metadata' => ['is_visual' => true, 'render_engine' => 'd3']
            ],
            [
                'suffix' => 'SET', 'icon' => 'cog-8-tooth',
                'name' => ['ar' => 'إعدادات الهيكل', 'en' => 'Org Settings'],
                'route' => 'hr.org.settings',
                'description' => ['ar' => 'إعدادات وقواعد الهيكل التنظيمي', 'en' => 'Configure organizational structure settings'],
                'metadata' => ['config' => true]
            ],
        ]);

// شؤون الموظفين (HR-EMP)
        $this->seedAppLayer('HR-EMP', [
            [
                'suffix' => 'REG', 'icon' => 'users',
                'name' => ['ar' => 'سجل الموظفين', 'en' => 'Employee Register'],
                'route' => 'hr.employees.register',
                'description' => ['ar' => 'إدارة ملفات الموظفين والبيانات الشخصية', 'en' => 'Manage employee profiles and personal data'],
                'metadata' => ['has_attachments' => true, 'biometric_linked' => true],
                'settings' => ['auto_generate_code' => true, 'code_prefix' => 'EMP-']
            ],
            [
                'suffix' => 'CON', 'icon' => 'document-text',
                'name' => ['ar' => 'العقود', 'en' => 'Contracts'],
                'route' => 'hr.employees.contracts',
                'description' => ['ar' => 'إدارة عقود العمل والمدد القانونية', 'en' => 'Manage employment contracts and terms'],
                'metadata' => ['requires_approval' => true, 'alert_on_expiry' => true],
                'settings' => ['expiry_notification_days' => 30]
            ],
            [
                'suffix' => 'DOC', 'icon' => 'folder-open',
                'name' => ['ar' => 'المستندات', 'en' => 'Documents'],
                'route' => 'hr.employees.documents',
                'description' => ['ar' => 'أرشفة الوثائق الرسمية للموظفين', 'en' => 'Archive employee official documents'],
                'metadata' => ['cloud_storage' => true]
            ],
            [
                'suffix' => 'ALC', 'icon' => 'user-plus',
                'name' => ['ar' => 'توزيع الموظفين', 'en' => 'Allocation'],
                'route' => 'hr.employees.allocation',
                'description' => ['ar' => 'توزيع الموظفين على المشاريع ومراكز التكلفة', 'en' => 'Allocate employees to projects and cost centers'],
                'metadata' => ['financial_sync' => true]
            ],
            [
                'suffix' => 'CUS', 'icon' => 'briefcase',
                'name' => ['ar' => 'العهد العينية', 'en' => 'Custody'],
                'route' => 'hr.employees.custody',
                'description' => ['ar' => 'إدارة العهد والأدوات المسلمة للموظف', 'en' => 'Manage assets and tools assigned to employees'],
                'metadata' => ['inventory_linked' => true]
            ],
            [
                'suffix' => 'SET', 'icon' => 'cog-8-tooth',
                'name' => ['ar' => 'إعدادات شؤون الموظفين', 'en' => 'Settings'],
                'route' => 'hr.employees.settings',
                'description' => ['ar' => 'إعدادات قواعد بيانات الموظفين', 'en' => 'Configure personnel database settings'],
                'metadata' => ['config' => true]
            ],
        ]);

// الحضور والانصراف (HR-ATT)
        $this->seedAppLayer('HR-ATT', [
            [
                'suffix' => 'SHF', 'icon' => 'clock',
                'name' => ['ar' => 'جداول الورديات', 'en' => 'Shifts'],
                'route' => 'hr.attendance.shifts',
                'description' => ['ar' => 'إدارة فترات العمل والجداول الزمنية', 'en' => 'Manage work shifts and time schedules'],
                'metadata' => ['is_flexible' => true]
            ],
            [
                'suffix' => 'POL', 'icon' => 'shield-check',
                'name' => ['ar' => 'السياسات', 'en' => 'Policies'],
                'route' => 'hr.attendance.policies',
                'description' => ['ar' => 'قواعد الحضور والتأخير والغياب', 'en' => 'Attendance, lateness, and absence rules'],
                'settings' => ['grace_period_minutes' => 15, 'overtime_calculation' => 'standard']
            ],
            [
                'suffix' => 'LEV', 'icon' => 'calendar-days',
                'name' => ['ar' => 'الإجازات', 'en' => 'Leaves'],
                'route' => 'hr.attendance.leaves',
                'description' => ['ar' => 'إدارة طلبات وأرصدة إجازات الموظفين', 'en' => 'Manage employee leave requests and balances'],
                'metadata' => ['balance_auto_reset' => true],
                'settings' => ['leave_request_limit_days' => 30]
            ],
            [
                'suffix' => 'LOG', 'icon' => 'finger-print',
                'name' => ['ar' => 'سجلات البصمة', 'en' => 'Biometric Logs'],
                'route' => 'hr.attendance.logs',
                'description' => ['ar' => 'سحب ومراجعة بيانات أجهزة البصمة', 'en' => 'Import and review biometric device logs'],
                'metadata' => ['api_sync' => true]
            ],
            [
                'suffix' => 'PRC', 'icon' => 'arrow-path-rounded-square',
                'name' => ['ar' => 'معالجة الدوام', 'en' => 'Processing'],
                'route' => 'hr.attendance.processing',
                'description' => ['ar' => 'احتساب ساعات العمل الإضافي والعجز', 'en' => 'Calculate overtime and shortage hours'],
                'metadata' => ['bulk_processing' => true]
            ],
            [
                'suffix' => 'SET', 'icon' => 'cog-8-tooth',
                'name' => ['ar' => 'إعدادات الحضور', 'en' => 'Settings'],
                'route' => 'hr.attendance.settings',
                'description' => ['ar' => 'إعدادات الربط مع أجهزة الحضور', 'en' => 'Configure attendance device integration'],
                'metadata' => ['config' => true]
            ],
        ]);

// الرواتب والأجور (HR-PAY)
        $this->seedAppLayer('HR-PAY', [
            [
                'suffix' => 'INC', 'icon' => 'plus-circle',
                'name' => ['ar' => 'الاستحقاقات', 'en' => 'Income'],
                'route' => 'hr.payroll.income',
                'description' => ['ar' => 'إدارة البدلات والمكافآت والعمولات', 'en' => 'Manage allowances, bonuses, and commissions'],
                'metadata' => ['taxable_filter' => true]
            ],
            [
                'suffix' => 'DED', 'icon' => 'minus-circle',
                'name' => ['ar' => 'الاستقطاعات', 'en' => 'Deductions'],
                'route' => 'hr.payroll.deductions',
                'description' => ['ar' => 'إدارة الخصومات والتأمينات والضرائب', 'en' => 'Manage deductions, insurance, and taxes'],
                'metadata' => ['social_security_linked' => true]
            ],
            [
                'suffix' => 'STR', 'icon' => 'document-duplicate',
                'name' => ['ar' => 'هياكل الرواتب', 'en' => 'Structures'],
                'route' => 'hr.payroll.structures',
                'description' => ['ar' => 'بناء حزم الرواتب للمجموعات الوظيفية', 'en' => 'Build salary packages for job groups'],
                'metadata' => ['template_engine' => true]
            ],
            [
                'suffix' => 'ADV', 'icon' => 'credit-card',
                'name' => ['ar' => 'السلف', 'en' => 'Advances'],
                'route' => 'hr.payroll.advances',
                'description' => ['ar' => 'إدارة سلف الموظفين وأقساط الاسترداد', 'en' => 'Manage employee loans and recovery installments'],
                'settings' => ['max_advance_percentage' => 50]
            ],
            [
                'suffix' => 'RUN', 'icon' => 'calculator',
                'name' => ['ar' => 'مسير الرواتب', 'en' => 'Payroll Run'],
                'route' => 'hr.payroll.run',
                'description' => ['ar' => 'احتساب وإصدار مسيرات الرواتب الشهرية', 'en' => 'Process and issue monthly payroll'],
                'metadata' => ['impacts_gl' => true, 'workflow' => 'payroll_closing'],
                'settings' => ['bank_file_format' => 'SAMA']
            ],
            [
                'suffix' => 'EOS', 'icon' => 'arrow-right-on-rectangle',
                'name' => ['ar' => 'نهاية الخدمة', 'en' => 'EOS'],
                'route' => 'hr.payroll.eos',
                'description' => ['ar' => 'احتساب مستحقات نهاية الخدمة والتسويات', 'en' => 'Calculate end of service benefits and settlements'],
                'metadata' => ['labor_law_compliant' => true]
            ],
            [
                'suffix' => 'SET', 'icon' => 'cog-8-tooth',
                'name' => ['ar' => 'إعدادات الرواتب', 'en' => 'Settings'],
                'route' => 'hr.payroll.settings',
                'description' => ['ar' => 'قواعد الاحتساب والربط المحاسبي للرواتب', 'en' => 'Payroll calculation rules and GL integration'],
                'metadata' => ['config' => true]
            ],
        ]);

// التوظيف (HR-REC)
        $this->seedAppLayer('HR-REC', [
            [
                'suffix' => 'REQ', 'icon' => 'megaphone',
                'name' => ['ar' => 'طلبات الاحتياج', 'en' => 'Requisitions'],
                'route' => 'hr.recruitment.requisitions',
                'description' => ['ar' => 'إدارة طلبات التوظيف من الأقسام', 'en' => 'Manage recruitment requests from departments'],
                'metadata' => ['budget_validation' => true]
            ],
            [
                'suffix' => 'CAN', 'icon' => 'user-group',
                'name' => ['ar' => 'المتقدمين', 'en' => 'Candidates'],
                'route' => 'hr.recruitment.candidates',
                'description' => ['ar' => 'قاعدة بيانات المتقدمين والسير الذاتية', 'en' => 'Candidates database and CV bank'],
                'metadata' => ['parsing_enabled' => true]
            ],
            [
                'suffix' => 'INT', 'icon' => 'chat-bubble-left-right',
                'name' => ['ar' => 'المقابلات', 'en' => 'Interviews'],
                'route' => 'hr.recruitment.interviews',
                'description' => ['ar' => 'جدولة وتقييم المقابلات الوظيفية', 'en' => 'Schedule and evaluate job interviews'],
                'settings' => ['reminder_before_hours' => 2]
            ],
            [
                'suffix' => 'OFR', 'icon' => 'document-check',
                'name' => ['ar' => 'عروض العمل', 'en' => 'Job Offers'],
                'route' => 'hr.recruitment.offers',
                'description' => ['ar' => 'إصدار ومتابعة عروض العمل للمرشحين', 'en' => 'Issue and track job offers for candidates'],
                'metadata' => ['digital_signature' => true]
            ],
            [
                'suffix' => 'SET', 'icon' => 'cog-8-tooth',
                'name' => ['ar' => 'إعدادات التوظيف', 'en' => 'Settings'],
                'route' => 'hr.recruitment.settings',
                'description' => ['ar' => 'إعدادات مراحل التوظيف والاختبارات', 'en' => 'Configure recruitment stages and tests'],
                'metadata' => ['config' => true]
            ],
        ]);

// تقييم الأداء (HR-PER)
        $this->seedAppLayer('HR-PER', [
            [
                'suffix' => 'KPI', 'icon' => 'bullseye',
                'name' => ['ar' => 'مؤشرات الأداء', 'en' => 'KPIs'],
                'route' => 'hr.performance.kpis',
                'description' => ['ar' => 'تعريف مؤشرات الأداء والأهداف السنوية', 'en' => 'Define Key Performance Indicators and targets'],
                'metadata' => ['smart_goals' => true]
            ],
            [
                'suffix' => 'APP', 'icon' => 'clipboard-document-check',
                'name' => ['ar' => 'التقييمات', 'en' => 'Appraisals'],
                'route' => 'hr.performance.appraisals',
                'description' => ['ar' => 'إدارة نماذج ودورات تقييم الموظفين', 'en' => 'Manage employee appraisal forms and cycles'],
                'metadata' => ['360_feedback' => false],
                'settings' => ['default_appraisal_period' => 'annual']
            ],
            [
                'suffix' => 'INC', 'icon' => 'gift',
                'name' => ['ar' => 'الحوافز', 'en' => 'Incentives'],
                'route' => 'hr.performance.incentives',
                'description' => ['ar' => 'ربط نتائج الأداء بالمكافآت والحوافز', 'en' => 'Link performance results to rewards and incentives'],
                'metadata' => ['auto_calculation' => true]
            ],
            [
                'suffix' => 'TRN', 'icon' => 'academic-cap',
                'name' => ['ar' => 'التدريب', 'en' => 'Training'],
                'route' => 'hr.performance.training',
                'description' => ['ar' => 'إدارة الدورات التدريبية والاحتياجات التطويرية', 'en' => 'Manage training courses and development needs'],
                'metadata' => ['certificate_tracking' => true]
            ],
            [
                'suffix' => 'SET', 'icon' => 'cog-8-tooth',
                'name' => ['ar' => 'إعدادات التقييم', 'en' => 'Settings'],
                'route' => 'hr.performance.settings',
                'description' => ['ar' => 'إعدادات معايير التقييم والتدريب', 'en' => 'Configure appraisal and training settings'],
                'metadata' => ['config' => true]
            ],
        ]);

// الخدمة الذاتية (HR-ESS)
        $this->seedAppLayer('HR-ESS', [
            [
                'suffix' => 'LEV', 'icon' => 'calendar-days',
                'name' => ['ar' => 'طلبات الإجازات', 'en' => 'Leaves'],
                'route' => 'hr.ess.leaves',
                'description' => ['ar' => 'تقديم ومتابعة طلبات الإجازات الشخصية', 'en' => 'Submit and track personal leave requests'],
                'metadata' => ['mobile_friendly' => true]
            ],
            [
                'suffix' => 'ADV', 'icon' => 'banknotes',
                'name' => ['ar' => 'طلبات السلف', 'en' => 'Advances'],
                'route' => 'hr.ess.advances',
                'description' => ['ar' => 'تقديم طلبات السلف المالية الشخصية', 'en' => 'Submit personal financial advance requests'],
                'metadata' => ['direct_approval_chain' => true]
            ],
            [
                'suffix' => 'LET', 'icon' => 'envelope',
                'name' => ['ar' => 'الخطابات', 'en' => 'HR Letters'],
                'route' => 'hr.ess.letters',
                'description' => ['ar' => 'طلب خطابات التعريف والشهادات الإدارية', 'en' => 'Request HR letters and certificates'],
                'settings' => ['auto_generate_pdf' => true, 'qr_verification' => true]
            ],
            [
                'suffix' => 'PAY', 'icon' => 'document-text',
                'name' => ['ar' => 'قسيمة الراتب', 'en' => 'Payslip'],
                'route' => 'hr.ess.payslips',
                'description' => ['ar' => 'استعراض وتحميل قسائم الرواتب الشهرية', 'en' => 'View and download monthly payslips'],
                'metadata' => ['read_only' => true]
            ],
            [
                'suffix' => 'ATT', 'icon' => 'clock',
                'name' => ['ar' => 'حضوري', 'en' => 'Attendance'],
                'route' => 'hr.ess.attendance',
                'description' => ['ar' => 'عرض سجل الحضور والانصراف اليومي للموظف', 'en' => 'View employee daily attendance logs'],
                'metadata' => ['real_time_tracking' => true]
            ],
            [
                'suffix' => 'SET', 'icon' => 'cog-8-tooth',
                'name' => ['ar' => 'إعدادات الخدمة الذاتية', 'en' => 'Settings'],
                'route' => 'hr.ess.settings',
                'description' => ['ar' => 'إعدادات بوابة الموظف والصلاحيات', 'en' => 'Configure employee portal settings and permissions'],
                'metadata' => ['config' => true]
            ],
        ]);
        // ==========================================
// قطاع الإعدادات العامة (GEN Applications)
// ==========================================

// العالم والبيانات المركزية (GEN-WRD)
        $this->seedAppLayer('GEN-WRD', [
            [
                'suffix' => 'PPL', 'icon' => 'user-group',
                'name' => ['ar' => 'دليل الأشخاص', 'en' => 'People'],
                'route' => 'general.world.people',
                'description' => ['ar' => 'إدارة سجل الأشخاص المركزي والعلاقات', 'en' => 'Manage central people directory and relationships'],
                'metadata' => ['is_core' => true]
            ],
            [
                'suffix' => 'COM', 'icon' => 'building-office-2',
                'name' => ['ar' => 'الشركات', 'en' => 'Companies'],
                'route' => 'general.world.companies',
                'description' => ['ar' => 'دليل الشركات والكيانات الخارجية', 'en' => 'External companies and entities directory'],
                'metadata' => ['has_crm_link' => true]
            ],
            [
                'suffix' => 'CNT', 'icon' => 'flag',
                'name' => ['ar' => 'الدول', 'en' => 'Countries'],
                'route' => 'general.world.countries',
                'description' => ['ar' => 'إدارة الدول ورموز الاتصال والمواصفات', 'en' => 'Manage countries, dial codes and specs'],
                'metadata' => ['static_data' => true]
            ],
            [
                'suffix' => 'STA', 'icon' => 'map',
                'name' => ['ar' => 'المحافظات', 'en' => 'States'],
                'route' => 'general.world.states',
                'description' => ['ar' => 'إدارة المحافظات والأقاليم الجغرافية', 'en' => 'Manage states and geographic regions']
            ],
            [
                'suffix' => 'CTY', 'icon' => 'map-pin',
                'name' => ['ar' => 'المدن', 'en' => 'Cities'],
                'route' => 'general.world.cities',
                'description' => ['ar' => 'إدارة المدن والمناطق التابعة للمحافظات', 'en' => 'Manage cities and areas within states']
            ],
            [
                'suffix' => 'CUR', 'icon' => 'banknotes',
                'name' => ['ar' => 'العملات', 'en' => 'Currencies'],
                'route' => 'general.world.currencies',
                'description' => ['ar' => 'إدارة العملات وأسعار الصرف التاريخية', 'en' => 'Manage currencies and historical exchange rates'],
                'settings' => ['base_currency' => 'EGP', 'auto_update_rates' => false]
            ],
            [
                'suffix' => 'TMZ', 'icon' => 'clock',
                'name' => ['ar' => 'المناطق الزمنية', 'en' => 'Timezones'],
                'route' => 'general.world.timezones',
                'description' => ['ar' => 'إعداد فروق التوقيت والمناطق الزمنية', 'en' => 'Setup time offsets and timezones']
            ],
        ]);

// إعدادات النظام (GEN-SET)
        $this->seedAppLayer('GEN-SET', [
            [
                'suffix' => 'MOD', 'icon' => 'squares-plus',
                'name' => ['ar' => 'الموديولات', 'en' => 'Modules'],
                'route' => 'general.settings.modules',
                'description' => ['ar' => 'إدارة وتفعيل الموديولات البرمجية', 'en' => 'Manage and activate software modules'],
                'metadata' => ['super_admin_only' => true]
            ],
            [
                'suffix' => 'BND', 'icon' => 'paint-brush',
                'name' => ['ar' => 'الهوية', 'en' => 'Branding'],
                'route' => 'general.settings.branding',
                'description' => ['ar' => 'تخصيص الشعارات والألوان والهوية البصرية', 'en' => 'Customize logos, colors and visual identity'],
                'settings' => ['primary_color' => '#1e40af', 'logo_height' => '40px']
            ],
            [
                'suffix' => 'API', 'icon' => 'cpu-chip',
                'name' => ['ar' => 'الربط', 'en' => 'API Gateway'],
                'route' => 'general.settings.api',
                'description' => ['ar' => 'إدارة مفاتيح الربط والخدمات الخارجية', 'en' => 'Manage API keys and external services'],
                'metadata' => ['security_level' => 'high']
            ],
            [
                'suffix' => 'SEQ', 'icon' => 'numbered-list',
                'name' => ['ar' => 'الترقيم', 'en' => 'Sequences'],
                'route' => 'general.settings.sequences',
                'description' => ['ar' => 'ضبط تسلسل ترقيم السندات والفواتير', 'en' => 'Configure document and invoice numbering sequences'],
                'settings' => ['reset_annually' => true, 'padding_length' => 6]
            ],
            [
                'suffix' => 'DEF', 'icon' => 'adjustments-horizontal',
                'name' => ['ar' => 'الإعدادات الافتراضية', 'en' => 'Defaults'],
                'route' => 'general.settings.defaults',
                'description' => ['ar' => 'ضبط القيم الافتراضية العامة للنظام', 'en' => 'Set general system default values'],
                'metadata' => ['config' => true]
            ],
        ]);

// الأمان والصلاحيات (GEN-SEC)
        $this->seedAppLayer('GEN-SEC', [
            [
                'suffix' => 'USR', 'icon' => 'user-circle',
                'name' => ['ar' => 'المستخدمين', 'en' => 'Users'],
                'route' => 'general.security.users',
                'description' => ['ar' => 'إدارة حسابات المستخدمين والوصول', 'en' => 'Manage user accounts and access'],
                'metadata' => ['auth_linked' => true]
            ],
            [
                'suffix' => 'ROL', 'icon' => 'shield-check',
                'name' => ['ar' => 'الصلاحيات', 'en' => 'Roles'],
                'route' => 'general.security.roles',
                'description' => ['ar' => 'إدارة الأدوار ومصفوفة الصلاحيات', 'en' => 'Manage roles and permissions matrix'],
                'metadata' => ['is_rbac' => true]
            ],
            [
                'suffix' => 'FRM', 'icon' => 'lock-closed',
                'name' => ['ar' => 'صلاحيات الحقول', 'en' => 'Field Security'],
                'route' => 'general.security.fields',
                'description' => ['ar' => 'التحكم في ظهور وصلاحية الحقول للمستخدمين', 'en' => 'Control field visibility and access for users']
            ],
            [
                'suffix' => 'BCH', 'icon' => 'building-library',
                'name' => ['ar' => 'صلاحيات الفروع', 'en' => 'Branch Access'],
                'route' => 'general.security.branches',
                'description' => ['ar' => 'تخصيص وصول المستخدمين للفروع والشركات', 'en' => 'Assign user access to branches and companies']
            ],
            [
                'suffix' => 'POL', 'icon' => 'key',
                'name' => ['ar' => 'سياسات الحماية', 'en' => 'Security Policies'],
                'route' => 'general.security.policies',
                'description' => ['ar' => 'إدارة سياسات كلمة المرور والجلسات', 'en' => 'Manage password and session policies'],
                'settings' => ['password_expiry_days' => 90, 'two_factor_auth' => false]
            ],
            [
                'suffix' => 'LOG', 'icon' => 'list-bullet',
                'name' => ['ar' => 'سجل الدخول', 'en' => 'Login History'],
                'route' => 'general.security.logs',
                'description' => ['ar' => 'تتبع محاولات الدخول وعناوين الشبكة', 'en' => 'Track login attempts and IP addresses'],
                'metadata' => ['read_only' => true]
            ],
        ]);

// التدقيق والسجلات (GEN-AUD)
        $this->seedAppLayer('GEN-AUD', [
            [
                'suffix' => 'TRK', 'icon' => 'magnifying-glass-circle',
                'name' => ['ar' => 'تتبع التغييرات', 'en' => 'Change Tracking'],
                'route' => 'general.audit.tracking',
                'description' => ['ar' => 'مراقبة التعديلات على البيانات (Audit Trail)', 'en' => 'Monitor data modifications (Audit Trail)'],
                'metadata' => ['is_audit' => true]
            ],
            [
                'suffix' => 'HST', 'icon' => 'clock-history',
                'name' => ['ar' => 'تاريخ السجلات', 'en' => 'Record History'],
                'route' => 'general.audit.history',
                'description' => ['ar' => 'سجل الحركات التاريخي لكل مستند', 'en' => 'Historical transaction log for each document']
            ],
            [
                'suffix' => 'SYS', 'icon' => 'bug-ant',
                'name' => ['ar' => 'أخطاء النظام', 'en' => 'System Logs'],
                'route' => 'general.audit.system-logs',
                'description' => ['ar' => 'سجل الأخطاء التقنية وأداء الخادم', 'en' => 'Technical errors and server performance logs'],
                'settings' => ['log_retention_days' => 30]
            ],
            [
                'suffix' => 'SET', 'icon' => 'adjustments-vertical',
                'name' => ['ar' => 'الإعدادات', 'en' => 'Settings'],
                'route' => 'general.audit.settings',
                'description' => ['ar' => 'إعدادات سياسات الأرشفة والتدقيق', 'en' => 'Configure audit and archiving policies']
            ],
        ]);

// الإشعارات (GEN-NOT)
        $this->seedAppLayer('GEN-NOT', [
            [
                'suffix' => 'MYN', 'icon' => 'bell-alert',
                'name' => ['ar' => 'إشعاراتي', 'en' => 'My Notifications'],
                'route' => 'general.notifications.mine',
                'description' => ['ar' => 'مركز تنبيهات المستخدم والرسائل', 'en' => 'User notification center and messages'],
                'metadata' => ['is_personal' => true]
            ],
            [
                'suffix' => 'TMP', 'icon' => 'document-duplicate',
                'name' => ['ar' => 'القوالب', 'en' => 'Templates'],
                'route' => 'general.notifications.templates',
                'description' => ['ar' => 'إدارة قوالب البريد والإشعارات', 'en' => 'Manage email and notification templates']
            ],
            [
                'suffix' => 'EVT', 'icon' => 'bolt',
                'name' => ['ar' => 'الأحداث', 'en' => 'Events'],
                'route' => 'general.notifications.events',
                'description' => ['ar' => 'ربط أحداث النظام بالإشعارات التلقائية', 'en' => 'Link system events to auto-notifications']
            ],
            [
                'suffix' => 'SET', 'icon' => 'user-minus',
                'name' => ['ar' => 'التفضيلات', 'en' => 'Preferences'],
                'route' => 'general.notifications.settings',
                'description' => ['ar' => 'إعدادات قنوات الإرسال وتفضيلات المستخدم', 'en' => 'Configure delivery channels and user preferences']
            ],
        ]);

// إدارة الملفات (GEN-FIL)
        $this->seedAppLayer('GEN-FIL', [
            [
                'suffix' => 'LIB', 'icon' => 'folder-open',
                'name' => ['ar' => 'مكتبة الوسائط', 'en' => 'Media Library'],
                'route' => 'general.files.library',
                'description' => ['ar' => 'إدارة الصور والملفات المرفوعة', 'en' => 'Manage uploaded images and files'],
                'metadata' => ['has_preview' => true]
            ],
            [
                'suffix' => 'SEC', 'icon' => 'shield-exclamation',
                'name' => ['ar' => 'الأمان', 'en' => 'Security'],
                'route' => 'general.files.security',
                'description' => ['ar' => 'إدارة صلاحيات الوصول للمجلدات والملفات', 'en' => 'Manage folder and file access permissions']
            ],
            [
                'suffix' => 'ARC', 'icon' => 'archive-box-arrow-down',
                'name' => ['ar' => 'الأرشيف', 'en' => 'E-Archiving'],
                'route' => 'general.files.archive',
                'description' => ['ar' => 'الأرشيف الإلكتروني والنسخ الاحتياطي', 'en' => 'E-Archiving and backup management'],
                'metadata' => ['cloud_sync' => true]
            ],
            [
                'suffix' => 'SET', 'icon' => 'cloud-arrow-up',
                'name' => ['ar' => 'إعدادات التخزين', 'en' => 'Settings'],
                'route' => 'general.files.settings',
                'description' => ['ar' => 'إعدادات سعات التخزين وأنواع الملفات', 'en' => 'Configure storage capacities and file types'],
                'settings' => ['max_upload_size_mb' => 20, 'allowed_extensions' => ['pdf', 'jpg', 'png', 'docx']]
            ],
        ]);

// التطبيقات المساعدة (GEN-APP)
        $this->seedAppLayer('GEN-APP', [
            [
                'suffix' => 'CHT', 'icon' => 'chat-bubble-left-right',
                'name' => ['ar' => 'المحادثات', 'en' => 'Chat'],
                'route' => 'general.apps.chat',
                'description' => ['ar' => 'نظام المراسلة الداخلية والتعاون', 'en' => 'Internal messaging and collaboration system'],
                'metadata' => ['realtime' => true]
            ],
            [
                'suffix' => 'IBX', 'icon' => 'inbox-stack',
                'name' => ['ar' => 'صندوق المراسلات', 'en' => 'Inbox'],
                'route' => 'general.apps.inbox',
                'description' => ['ar' => 'إدارة المعاملات والمراسلات الرسمية', 'en' => 'Manage official transactions and correspondence']
            ],
            [
                'suffix' => 'CAL', 'icon' => 'calendar-days',
                'name' => ['ar' => 'التقويم', 'en' => 'Calendar'],
                'route' => 'general.apps.calendar',
                'description' => ['ar' => 'تقويم الأحداث والمواعيد المشتركة', 'en' => 'Shared events and appointments calendar']
            ],
            [
                'suffix' => 'TSK', 'icon' => 'clipboard-document-list',
                'name' => ['ar' => 'المهام', 'en' => 'Tasks'],
                'route' => 'general.apps.tasks',
                'description' => ['ar' => 'نظام إدارة المهام الفردية والجماعية', 'en' => 'Individual and group task management system'],
                'metadata' => ['kanban_view' => true]
            ],
            [
                'suffix' => 'NOT', 'icon' => 'pencil-square',
                'name' => ['ar' => 'المفكرة', 'en' => 'Notes'],
                'route' => 'general.apps.notes',
                'description' => ['ar' => 'المفكرة الشخصية وتدوين الملاحظات', 'en' => 'Personal notepad and note-taking']
            ],
        ]);

        // ==========================================
// قطاع المشتريات والموردين (PUR Applications)
// ==========================================

// عقود المشتريات (PUR-CON)
        $this->seedAppLayer('PUR-CON', [
            [
                'suffix' => 'MNG', 'icon' => 'document-text',
                'name' => ['ar' => 'العقود', 'en' => 'Contracts'],
                'route' => 'purchasing.contracts.manage',
                'description' => ['ar' => 'إدارة عقود التوريد والاتفاقيات الإطارية', 'en' => 'Manage supply contracts and framework agreements'],
                'metadata' => ['is_core' => true]
            ],
            [
                'suffix' => 'ITM', 'icon' => 'list-bullet',
                'name' => ['ar' => 'البنود', 'en' => 'Items'],
                'route' => 'purchasing.contracts.items',
                'description' => ['ar' => 'إدارة بنود ومواد العقود المتعاقد عليها', 'en' => 'Manage contract line items and materials']
            ],
            [
                'suffix' => 'PRC', 'icon' => 'tag',
                'name' => ['ar' => 'الأسعار', 'en' => 'Prices'],
                'route' => 'purchasing.contracts.prices',
                'description' => ['ar' => 'ضبط قوائم أسعار الموردين المتفق عليها', 'en' => 'Set agreed vendor price lists'],
                'settings' => ['allow_price_override' => false]
            ],
            [
                'suffix' => 'PAY', 'icon' => 'credit-card',
                'name' => ['ar' => 'شروط الدفع', 'en' => 'Payment Terms'],
                'route' => 'purchasing.contracts.payments',
                'description' => ['ar' => 'إدارة فترات السداد والاحتفاظات المالية', 'en' => 'Manage payment terms and financial retentions']
            ],
            [
                'suffix' => 'DOC', 'icon' => 'paper-clip',
                'name' => ['ar' => 'المرفقات', 'en' => 'Documents'],
                'route' => 'purchasing.contracts.documents',
                'description' => ['ar' => 'أرشفة النسخ الضوئية من العقود والمستندات', 'en' => 'Archive scanned copies of contracts and documents']
            ],
        ]);

// طلبات الشراء (PUR-REQ)
        $this->seedAppLayer('PUR-REQ', [
            [
                'suffix' => 'CRT', 'icon' => 'document-plus',
                'name' => ['ar' => 'إنشاء طلب', 'en' => 'Create Request'],
                'route' => 'purchasing.requisitions.create',
                'description' => ['ar' => 'إنشاء طلب احتياج مواد أو خدمات من الأقسام', 'en' => 'Create material or service requisition from departments'],
                'metadata' => ['workflow' => 'purchase_request']
            ],
            [
                'suffix' => 'APP', 'icon' => 'check-badge',
                'name' => ['ar' => 'الاعتماد', 'en' => 'Approvals'],
                'route' => 'purchasing.requisitions.approvals',
                'description' => ['ar' => 'مراجعة واعتماد طلبات الشراء المعلقة', 'en' => 'Review and approve pending purchase requisitions'],
                'metadata' => ['is_approval_screen' => true]
            ],
            [
                'suffix' => 'CNS', 'icon' => 'arrows-pointing-in',
                'name' => ['ar' => 'تجميع', 'en' => 'Consolidate'],
                'route' => 'purchasing.requisitions.consolidate',
                'description' => ['ar' => 'تجميع طلبات الشراء المتشابهة في طلب واحد', 'en' => 'Consolidate similar requisitions into one request'],
                'settings' => ['allow_cross_department_consolidation' => true]
            ],
            [
                'suffix' => 'TRK', 'icon' => 'eye',
                'name' => ['ar' => 'متابعة', 'en' => 'Tracking'],
                'route' => 'purchasing.requisitions.tracking',
                'description' => ['ar' => 'تتبع حالة طلبات الشراء ومراحل تنفيذها', 'en' => 'Track status and execution stages of requisitions']
            ],
        ]);

// طلبات عروض الأسعار (PUR-RFQ)
        $this->seedAppLayer('PUR-RFQ', [
            [
                'suffix' => 'CRT', 'icon' => 'paper-airplane',
                'name' => ['ar' => 'إنشاء RFQ', 'en' => 'Create RFQ'],
                'route' => 'purchasing.rfq.create',
                'description' => ['ar' => 'طلب عروض أسعار من مجموعة موردين', 'en' => 'Request for Quotations from multiple vendors'],
                'settings' => ['min_vendors_required' => 3]
            ],
            [
                'suffix' => 'REC', 'icon' => 'arrow-down-on-square-stack',
                'name' => ['ar' => 'العروض', 'en' => 'Quotations'],
                'route' => 'purchasing.rfq.quotes',
                'description' => ['ar' => 'تسجيل وتحليل عروض الأسعار المستلمة', 'en' => 'Register and analyze received quotations']
            ],
            [
                'suffix' => 'CMP', 'icon' => 'swatch',
                'name' => ['ar' => 'المقارنة', 'en' => 'Comparison'],
                'route' => 'purchasing.rfq.compare',
                'description' => ['ar' => 'تحليل فني ومالي ومقارنة العروض', 'en' => 'Technical and financial comparison of quotes'],
                'metadata' => ['is_analytical' => true]
            ],
            [
                'suffix' => 'SEL', 'icon' => 'trophy',
                'name' => ['ar' => 'الترسية', 'en' => 'Awarding'],
                'route' => 'purchasing.rfq.award',
                'description' => ['ar' => 'ترسية العطاء واختيار المورد الفائز', 'en' => 'Award the bid and select the winning vendor'],
                'metadata' => ['requires_manager_seal' => true]
            ],
        ]);

// أوامر الشراء (PUR-ORD)
        $this->seedAppLayer('PUR-ORD', [
            [
                'suffix' => 'CRT', 'icon' => 'document-plus',
                'name' => ['ar' => 'أمر شراء', 'en' => 'Create PO'],
                'route' => 'purchasing.orders.create',
                'description' => ['ar' => 'إصدار أمر شراء رسمي للمورد', 'en' => 'Issue official purchase order to vendor'],
                'metadata' => ['financial_impact' => true]
            ],
            [
                'suffix' => 'MNG', 'icon' => 'briefcase',
                'name' => ['ar' => 'إدارة', 'en' => 'Management'],
                'route' => 'purchasing.orders.manage',
                'description' => ['ar' => 'إدارة ومتابعة أوامر الشراء القائمة', 'en' => 'Manage and follow up on active POs']
            ],
            [
                'suffix' => 'APP', 'icon' => 'check-badge',
                'name' => ['ar' => 'اعتماد', 'en' => 'Approvals'],
                'route' => 'purchasing.orders.approvals',
                'description' => ['ar' => 'اعتماد أوامر الشراء حسب الصلاحيات المالية', 'en' => 'Approve POs based on financial authority']
            ],
            [
                'suffix' => 'TRK', 'icon' => 'truck',
                'name' => ['ar' => 'تتبع', 'en' => 'Tracking'],
                'route' => 'purchasing.orders.tracking',
                'description' => ['ar' => 'تتبع شحن واستلام بنود أمر الشراء', 'en' => 'Track shipping and receipt of PO line items']
            ],
        ]);

// فواتير المشتريات (PUR-INV)
        $this->seedAppLayer('PUR-INV', [
            [
                'suffix' => 'ENT', 'icon' => 'document-duplicate',
                'name' => ['ar' => 'فاتورة مشتريات', 'en' => 'Invoice Entry'],
                'route' => 'purchasing.invoices.entry',
                'description' => ['ar' => 'تسجيل فاتورة المورد المالية', 'en' => 'Enter vendor financial invoice'],
                'metadata' => ['tax_linked' => true]
            ],
            [
                'suffix' => 'MTCH', 'icon' => 'scale',
                'name' => ['ar' => 'مطابقة', 'en' => 'Matching'],
                'route' => 'purchasing.invoices.match',
                'description' => ['ar' => 'مطابقة الفاتورة مع أمر الشراء وإشعار الاستلام', 'en' => 'Match invoice with PO and GRN'],
                'settings' => ['matching_type' => '3-way']
            ],
            [
                'suffix' => 'EXP', 'icon' => 'plus-circle',
                'name' => ['ar' => 'تكاليف', 'en' => 'Landed Costs'],
                'route' => 'purchasing.invoices.landed-costs',
                'description' => ['ar' => 'توزيع التكاليف الإضافية (شحن، جمارك) على الأصناف', 'en' => 'Allocate additional costs (shipping, customs) to items'],
                'metadata' => ['impacts_valuation' => true]
            ],
            [
                'suffix' => 'APP', 'icon' => 'shield-check',
                'name' => ['ar' => 'اعتماد', 'en' => 'Approvals'],
                'route' => 'purchasing.invoices.approvals',
                'description' => ['ar' => 'الاعتماد النهائي للفاتورة للتحويل للمالية', 'en' => 'Final invoice approval for finance transfer']
            ],
        ]);

// مدفوعات الموردين (PUR-PAY)
        $this->seedAppLayer('PUR-PAY', [
            [
                'suffix' => 'PYM', 'icon' => 'banknotes',
                'name' => ['ar' => 'المدفوعات', 'en' => 'Payments'],
                'route' => 'purchasing.payments.process',
                'description' => ['ar' => 'إصدار أوامر الدفع للموردين', 'en' => 'Process payment orders to vendors']
            ],
            [
                'suffix' => 'APP', 'icon' => 'check-circle',
                'name' => ['ar' => 'اعتماد', 'en' => 'Approvals'],
                'route' => 'purchasing.payments.approvals',
                'description' => ['ar' => 'اعتماد الدفعات المالية للموردين', 'en' => 'Approve financial payments to vendors'],
                'metadata' => ['is_financial' => true]
            ],
            [
                'suffix' => 'ADV', 'icon' => 'arrow-up-circle',
                'name' => ['ar' => 'دفعات مقدمة', 'en' => 'Advances'],
                'route' => 'purchasing.payments.advances',
                'description' => ['ar' => 'إدارة الدفعات المقدمة للموردين وتسويتها', 'en' => 'Manage and settle vendor advance payments']
            ],
            [
                'suffix' => 'STMT', 'icon' => 'document-chart-bar',
                'name' => ['ar' => 'كشف حساب', 'en' => 'Statement'],
                'route' => 'purchasing.payments.statement',
                'description' => ['ar' => 'عرض كشف حساب المورد والمستحقات', 'en' => 'View vendor account statement and outstandings'],
                'metadata' => ['is_report' => true]
            ],
        ]);

// مرتجعات المشتريات (PUR-RET)
        $this->seedAppLayer('PUR-RET', [
            [
                'suffix' => 'CRT', 'icon' => 'arrow-uturn-left',
                'name' => ['ar' => 'مرتجع', 'en' => 'Return'],
                'route' => 'purchasing.returns.create',
                'description' => ['ar' => 'إنشاء طلب إرجاع بضاعة للمورد', 'en' => 'Create goods return request to vendor'],
                'metadata' => ['inventory_linked' => true]
            ],
            [
                'suffix' => 'DBN', 'icon' => 'document-minus',
                'name' => ['ar' => 'إشعارات مدينة', 'en' => 'Debit Notes'],
                'route' => 'purchasing.returns.debit-notes',
                'description' => ['ar' => 'إصدار إشعارات الخصم المالية للمورد', 'en' => 'Issue financial debit notes to vendor'],
                'metadata' => ['is_financial' => true]
            ],
            [
                'suffix' => 'APP', 'icon' => 'shield-check',
                'name' => ['ar' => 'اعتماد', 'en' => 'Approvals'],
                'route' => 'purchasing.returns.approvals',
                'description' => ['ar' => 'اعتماد طلبات المرتجع والإشعارات المدينة', 'en' => 'Approve return requests and debit notes']
            ],
        ]);

// سجل الموردين (PUR-VND)
        $this->seedAppLayer('PUR-VND', [
            [
                'suffix' => 'MAS', 'icon' => 'user-plus',
                'name' => ['ar' => 'الموردين', 'en' => 'Master'],
                'route' => 'purchasing.vendors.master',
                'description' => ['ar' => 'قاعدة بيانات الموردين والبيانات الأساسية', 'en' => 'Vendors database and master data'],
                'settings' => ['auto_vendor_code' => true]
            ],
            [
                'suffix' => 'CAT', 'icon' => 'rectangle-group',
                'name' => ['ar' => 'تصنيفات', 'en' => 'Categories'],
                'route' => 'purchasing.vendors.categories',
                'description' => ['ar' => 'تصنيف الموردين حسب نوع النشاط أو القوة', 'en' => 'Classify vendors by activity or strength']
            ],
            [
                'suffix' => 'EVL', 'icon' => 'star',
                'name' => ['ar' => 'تقييم', 'en' => 'Evaluation'],
                'route' => 'purchasing.vendors.evaluation',
                'description' => ['ar' => 'تقييم أداء الموردين (الجودة، الالتزام)', 'en' => 'Evaluate vendor performance (Quality, Compliance)'],
                'metadata' => ['scoring_system' => true]
            ],
        ]);
        // ==========================================
// قطاع المبيعات (SAL Applications)
// ==========================================

// عقود المبيعات (SAL-CON)
        $this->seedAppLayer('SAL-CON', [
            [
                'suffix' => 'MNG', 'icon' => 'document-check',
                'name' => ['ar' => 'العقود', 'en' => 'Contracts'],
                'route' => 'sales.contracts.manage',
                'description' => ['ar' => 'إدارة عقود العملاء والاتفاقيات السنوية', 'en' => 'Manage customer contracts and annual agreements'],
                'metadata' => ['is_core' => true]
            ],
            [
                'suffix' => 'PRC', 'icon' => 'tag',
                'name' => ['ar' => 'قوائم الأسعار', 'en' => 'Price Lists'],
                'route' => 'sales.contracts.prices',
                'description' => ['ar' => 'إدارة قوائم أسعار البيع والخصومات', 'en' => 'Manage sales price lists and discounts'],
                'settings' => ['allow_below_cost' => false, 'tax_inclusive' => true]
            ],
        ]);

// عروض الأسعار (SAL-QUO)
        $this->seedAppLayer('SAL-QUO', [
            [
                'suffix' => 'CRT', 'icon' => 'document-plus',
                'name' => ['ar' => 'عروض الأسعار', 'en' => 'Quotations'],
                'route' => 'sales.quotes.create',
                'description' => ['ar' => 'إنشاء عروض أسعار رسمية للعملاء', 'en' => 'Create official customer quotations'],
                'metadata' => ['has_print_templates' => true],
                'settings' => ['validity_days' => 15]
            ],
            [
                'suffix' => 'APP', 'icon' => 'shield-check',
                'name' => ['ar' => 'الاعتماد', 'en' => 'Approvals'],
                'route' => 'sales.quotes.approvals',
                'description' => ['ar' => 'مراجعة واعتماد عروض الأسعار الكبرى', 'en' => 'Review and approve major sales quotations'],
                'metadata' => ['approval_workflow' => 'sales_manager']
            ],
            [
                'suffix' => 'STT', 'icon' => 'chart-bar',
                'name' => ['ar' => 'حالة العروض', 'en' => 'Status'],
                'route' => 'sales.quotes.status',
                'description' => ['ar' => 'تتبع حالة العروض (مقبول، مرفوض، منتهي)', 'en' => 'Track quote status (Accepted, Rejected, Expired)'],
                'metadata' => ['is_analytical' => true]
            ],
        ]);

// أوامر البيع (SAL-ORD)
        $this->seedAppLayer('SAL-ORD', [
            [
                'suffix' => 'CRT', 'icon' => 'document-check',
                'name' => ['ar' => 'أمر بيع', 'en' => 'Sales Order'],
                'route' => 'sales.orders.create',
                'description' => ['ar' => 'تحويل عروض الأسعار المقبولة إلى أوامر تنفيذ', 'en' => 'Convert accepted quotes into execution orders'],
                'metadata' => ['inventory_link' => true]
            ],
            [
                'suffix' => 'APP', 'icon' => 'shield-exclamation',
                'name' => ['ar' => 'الاعتماد', 'en' => 'Credit App'],
                'route' => 'sales.orders.credit-check',
                'description' => ['ar' => 'اعتماد الطلبات بناءً على السجل الائتماني للعميل', 'en' => 'Approve orders based on customer credit history'],
                'settings' => ['block_if_over_limit' => true]
            ],
            [
                'suffix' => 'SHP', 'icon' => 'shopping-bag',
                'name' => ['ar' => 'الشحنات', 'en' => 'Shipping'],
                'route' => 'sales.orders.shipping',
                'description' => ['ar' => 'إدارة عمليات الشحن وتجهيز الطلبات', 'en' => 'Manage shipping and order fulfillment'],
                'metadata' => ['logistics_linked' => true]
            ],
        ]);

// فواتير المبيعات (SAL-INV)
        $this->seedAppLayer('SAL-INV', [
            [
                'suffix' => 'GEN', 'icon' => 'document-duplicate',
                'name' => ['ar' => 'فاتورة مبيعات', 'en' => 'Invoices'],
                'route' => 'sales.invoices.index',
                'description' => ['ar' => 'إصدار فواتير المبيعات الضريبية', 'en' => 'Issue tax sales invoices'],
                'metadata' => ['zatca_phase' => 2, 'tax_linked' => true]
            ],
            [
                'suffix' => 'EINV', 'icon' => 'globe-alt',
                'name' => ['ar' => 'إلكترونية', 'en' => 'E-Invoicing'],
                'route' => 'sales.invoices.electronic',
                'description' => ['ar' => 'إدارة الربط مع بوابة الزكاة والدخل', 'en' => 'Manage integration with ZATCA portal'],
                'metadata' => ['is_api_service' => true],
                'settings' => ['environment' => 'production']
            ],
        ]);

// التحصيل والمديونيات (SAL-COL)
        $this->seedAppLayer('SAL-COL', [
            [
                'suffix' => 'RCP', 'icon' => 'banknotes',
                'name' => ['ar' => 'سندات القبض', 'en' => 'Receipts'],
                'route' => 'sales.collections.receipts',
                'description' => ['ar' => 'تسجيل المقبوضات النقدية والبنكية من العملاء', 'en' => 'Record cash and bank receipts from customers'],
                'metadata' => ['is_financial' => true]
            ],
            [
                'suffix' => 'AGN', 'icon' => 'chart-bar-square',
                'name' => ['ar' => 'أعمار الديون', 'en' => 'Aging'],
                'route' => 'sales.collections.aging',
                'description' => ['ar' => 'تحليل مديونيات العملاء حسب فترات التأخير', 'en' => 'Analyze customer debts by delay periods'],
                'metadata' => ['is_report' => true]
            ],
            [
                'suffix' => 'STMT', 'icon' => 'document-chart-bar',
                'name' => ['ar' => 'كشف حساب', 'en' => 'Statement'],
                'route' => 'sales.collections.statement',
                'description' => ['ar' => 'عرض وتحميل كشوف حسابات العملاء', 'en' => 'View and download customer account statements']
            ],
        ]);

// نقاط البيع (SAL-POS)
        $this->seedAppLayer('SAL-POS', [
            [
                'suffix' => 'REG', 'icon' => 'computer-desktop',
                'name' => ['ar' => 'الكاشير', 'en' => 'POS Register'],
                'route' => 'sales.pos.register',
                'description' => ['ar' => 'واجهة البيع السريع لنقاط البيع', 'en' => 'Quick sales interface for POS'],
                'settings' => ['touch_screen' => true, 'barcode_scanner' => true],
                'metadata' => ['ui_mode' => 'dark']
            ],
            [
                'suffix' => 'SES', 'icon' => 'key',
                'name' => ['ar' => 'الورديات', 'en' => 'Shifts'],
                'route' => 'sales.pos.shifts',
                'description' => ['ar' => 'إدارة وفتح وإغلاق ورديات الكاشير', 'en' => 'Manage opening and closing cashier shifts'],
                'metadata' => ['track_cash' => true]
            ],
            [
                'suffix' => 'XRC', 'icon' => 'calculator',
                'name' => ['ar' => 'الجرد', 'en' => 'Z-Report'],
                'route' => 'sales.pos.reports',
                'description' => ['ar' => 'تقارير الإغلاق اليومي ومطابقة النقدية', 'en' => 'Daily closing reports and cash reconciliation'],
                'metadata' => ['report_type' => 'Z-Report']
            ],
        ]);

        // ==========================================
// قطاع علاقات العملاء (CRM Applications)
// ==========================================

// إدارة العملاء المحتملين (CRM-LD)
        $this->seedAppLayer('CRM-LD', [
            [
                'suffix' => 'REG', 'icon' => 'user-plus',
                'name' => ['ar' => 'المحتملين', 'en' => 'Leads'],
                'route' => 'crm.leads.register',
                'description' => ['ar' => 'تسجيل وإدارة العملاء المحتملين والبيانات الأولية', 'en' => 'Register and manage potential leads and initial data'],
                'metadata' => ['allow_import' => true, 'source_tracking' => true],
                'settings' => ['duplicate_check_by' => 'email']
            ],
            [
                'suffix' => 'FLW', 'icon' => 'arrow-path',
                'name' => ['ar' => 'المتابعة', 'en' => 'Follow-up'],
                'route' => 'crm.leads.followup',
                'description' => ['ar' => 'جدولة ومتابعة التواصل مع العملاء المحتملين', 'en' => 'Schedule and track communication with leads'],
                'metadata' => ['calendar_view' => true],
                'settings' => ['default_reminder_days' => 2]
            ],
        ]);

// فرص المبيعات (CRM-OPP)
        $this->seedAppLayer('CRM-OPP', [
            [
                'suffix' => 'PIP', 'icon' => 'chart-bar',
                'name' => ['ar' => 'الفرص', 'en' => 'Pipeline'],
                'route' => 'crm.opportunities.pipeline',
                'description' => ['ar' => 'إدارة مراحل فرص المبيعات المتوقعة', 'en' => 'Manage stages of expected sales opportunities'],
                'metadata' => ['is_kanban' => true, 'view_mode' => 'pipeline'],
                'settings' => ['probabilty_calculation' => 'automatic']
            ],
            [
                'suffix' => 'WON', 'icon' => 'trophy',
                'name' => ['ar' => 'الفرص الرابحة', 'en' => 'Won'],
                'route' => 'crm.opportunities.won',
                'description' => ['ar' => 'سجل الصفقات والفرص التي تم إغلاقها بنجاح', 'en' => 'Log of successfully closed deals and opportunities'],
                'metadata' => ['conversion_enabled' => true, 'financial_sync' => true]
            ],
        ]);

// الأنشطة والفعاليات (CRM-ACT)
        $this->seedAppLayer('CRM-ACT', [
            [
                'suffix' => 'CAL', 'icon' => 'calendar',
                'name' => ['ar' => 'التقويم', 'en' => 'Calendar'],
                'route' => 'crm.activities.calendar',
                'description' => ['ar' => 'تقويم المواعيد والزيارات الميدانية للعملاء', 'en' => 'Calendar for appointments and customer site visits'],
                'metadata' => ['fullcalendar_integrated' => true]
            ],
            [
                'suffix' => 'LOG', 'icon' => 'phone',
                'name' => ['ar' => 'المكالمات', 'en' => 'Call Logs'],
                'route' => 'crm.activities.calls',
                'description' => ['ar' => 'تسجيل تفاصيل المكالمات الواردة والصادرة', 'en' => 'Log details of incoming and outgoing calls'],
                'metadata' => ['has_audio_links' => false],
                'settings' => ['recording_link_enabled' => false]
            ],
        ]);

// الحملات التسويقية (CRM-CMP)
        $this->seedAppLayer('CRM-CMP', [
            [
                'suffix' => 'CRT', 'icon' => 'megaphone',
                'name' => ['ar' => 'الحملات', 'en' => 'Campaigns'],
                'route' => 'crm.marketing.campaigns',
                'description' => ['ar' => 'إدارة الحملات الإعلانية وقياس العائد منها', 'en' => 'Manage advertising campaigns and measure ROI'],
                'metadata' => ['tracking_channels' => ['email', 'sms', 'social']],
                'settings' => ['default_currency' => 'USD']
            ],
        ]);

// سجل العملاء المركزي (CRM-CST)
        $this->seedAppLayer('CRM-CST', [
            [
                'suffix' => 'DIR', 'icon' => 'users',
                'name' => ['ar' => 'العملاء', 'en' => 'Customers'],
                'route' => 'crm.customers.index',
                'description' => ['ar' => 'قاعدة بيانات العملاء الشاملة وتاريخ التعاملات', 'en' => 'Comprehensive customer database and transaction history'],
                'metadata' => ['360_view' => true, 'is_core' => true],
                'settings' => ['auto_customer_code' => true, 'code_prefix' => 'CUS-']
            ],
        ]);
        // ==========================================
// قطاع إدارة المشاريع (PRJ Applications)
// ==========================================

// البيانات الأساسية للمشاريع (PRJ-MST)
        $this->seedAppLayer('PRJ-MST', [
            [
                'suffix' => 'CAT', 'icon' => 'folder-open',
                'name' => ['ar' => 'تصنيفات', 'en' => 'Categories'],
                'route' => 'projects.master.categories',
                'description' => ['ar' => 'تصنيف المشاريع حسب النوع أو الحجم', 'en' => 'Categorize projects by type or size'],
                'metadata' => ['is_core' => true]
            ],
            [
                'suffix' => 'MST', 'icon' => 'building-office-2',
                'name' => ['ar' => 'المشاريع', 'en' => 'Project Master'],
                'route' => 'projects.master.index',
                'description' => ['ar' => 'سجل بيانات المشاريع والمواقع المتعاقد عليها', 'en' => 'Register of projects and contracted sites'],
                'metadata' => ['has_gis' => true],
                'settings' => ['auto_project_code' => true]
            ],
        ]);

// التخطيط والجدولة (PRJ-PLN)
        $this->seedAppLayer('PRJ-PLN', [
            [
                'suffix' => 'WBS', 'icon' => 'list-bullet',
                'name' => ['ar' => 'هيكل العمل', 'en' => 'WBS'],
                'route' => 'projects.planning.wbs',
                'description' => ['ar' => 'هيكل تقسيم العمل وتوزيع الحزم التنفيذية', 'en' => 'Work Breakdown Structure and work packages'],
                'metadata' => ['is_hierarchical' => true]
            ],
            [
                'suffix' => 'GNT', 'icon' => 'chart-bar',
                'name' => ['ar' => 'مخطط جانت', 'en' => 'Gantt Chart'],
                'route' => 'projects.planning.gantt',
                'description' => ['ar' => 'الجدول الزمني ومتابعة المسار الحرج للمشروع', 'en' => 'Project timeline and critical path monitoring'],
                'metadata' => ['is_visual' => true],
                'settings' => ['default_view' => 'monthly']
            ],
        ]);

// الموارد والإمدادات (PRJ-RES)
        $this->seedAppLayer('PRJ-RES', [
            [
                'suffix' => 'STF', 'icon' => 'user-group',
                'name' => ['ar' => 'العمالة', 'en' => 'Staff'],
                'route' => 'projects.resources.staff',
                'description' => ['ar' => 'إدارة وتوزيع القوى العاملة على المشاريع', 'en' => 'Manage and allocate manpower to projects'],
                'metadata' => ['hr_linked' => true]
            ],
            [
                'suffix' => 'EQP', 'icon' => 'truck',
                'name' => ['ar' => 'المعدات', 'en' => 'Equipment'],
                'route' => 'projects.resources.equipment',
                'description' => ['ar' => 'توزيع وتتبع المعدات والآليات في المواقع', 'en' => 'Allocate and track equipment and machinery on sites'],
                'metadata' => ['fleet_linked' => true]
            ],
        ]);

// التكاليف والميزانية (PRJ-CST)
        $this->seedAppLayer('PRJ-CST', [
            [
                'suffix' => 'BGT', 'icon' => 'calculator',
                'name' => ['ar' => 'الميزانية', 'en' => 'Budgeting'],
                'route' => 'projects.costs.budget',
                'description' => ['ar' => 'تخطيط ميزانية المشروع التقديرية', 'en' => 'Plan the estimated project budget'],
                'metadata' => ['financial_sync' => true],
                'settings' => ['alert_on_overbudget' => true]
            ],
            [
                'suffix' => 'ACT', 'icon' => 'currency-dollar',
                'name' => ['ar' => 'التكاليف الفعلية', 'en' => 'Actual Costs'],
                'route' => 'projects.costs.actual',
                'description' => ['ar' => 'رصد ومقارنة التكاليف الفعلية بالمخطط له', 'en' => 'Monitor and compare actual costs vs planned'],
                'metadata' => ['is_analytical' => true]
            ],
        ]);

// المهام وسجلات الوقت (PRJ-TSK)
        $this->seedAppLayer('PRJ-TSK', [
            [
                'suffix' => 'LST', 'icon' => 'clipboard-document-list',
                'name' => ['ar' => 'المهام', 'en' => 'Task List'],
                'route' => 'projects.tasks.index',
                'description' => ['ar' => 'إدارة المهام اليومية وقوائم التنفيذ', 'en' => 'Manage daily tasks and execution lists'],
                'metadata' => ['kanban_enabled' => true]
            ],
            [
                'suffix' => 'TMS', 'icon' => 'clock',
                'name' => ['ar' => 'سجلات الوقت', 'en' => 'Timesheets'],
                'route' => 'projects.tasks.timesheets',
                'description' => ['ar' => 'تسجيل ساعات العمل الفعلية للموارد في المواقع', 'en' => 'Record actual work hours for resources on sites'],
                'settings' => ['require_manager_approval' => true]
            ],
        ]);

// الوثائق والعقود (PRJ-DOC)
        $this->seedAppLayer('PRJ-DOC', [
            [
                'suffix' => 'DWG', 'icon' => 'map',
                'name' => ['ar' => 'المخططات', 'en' => 'Drawings'],
                'route' => 'projects.documents.drawings',
                'description' => ['ar' => 'إدارة المخططات الهندسية والرسومات الفنية', 'en' => 'Manage engineering drawings and technical blue prints'],
                'metadata' => ['supports_versions' => true]
            ],
            [
                'suffix' => 'CON', 'icon' => 'document-check',
                'name' => ['ar' => 'العقود', 'en' => 'Contracts'],
                'route' => 'projects.documents.contracts',
                'description' => ['ar' => 'إدارة عقود المشروع مع الملاك والمقاولين', 'en' => 'Manage project contracts with owners and contractors'],
                'metadata' => ['legal_linked' => true]
            ],
        ]);
    }

    /**
     * المساعدة لطبقة الموديولات الفرعية
     */
    private function seedLayer(string $parentCode, array $subs): void
    {
        $parent = \App\Models\Module::where('code', $parentCode)->first();

        if ($parent) {
            foreach ($subs as $index => $sub) {
                \App\Models\Module::updateOrCreate(
                    ['code' => $sub['code']],
                    [
                        'parent_id'      => $parent->id,
                        'name'           => $sub['name'],
                        'description'    => $sub['description'],
                        'route'          => $sub['route'],
                        'type'           => 'folder',
                        'icon'           => $sub['icon'],
                        'permission_key' => strtolower(str_replace('-', '.', $sub['code'])),
                        'sort_order'     => $index + 1,
                        'level'          => 2,
                        'metadata'       => $sub['metadata'] ?? [], // إضافة هذا السطر
                        'settings'       => $sub['settings'] ?? [], // إضافة هذا السطر
                        'is_active'      => true,
                    ]
                );
            }
        }
    }
    /**
     * المساعدة لطبقة التطبيقات (Level 3)
     */
    private function seedAppLayer(string $subCode, array $apps): void
    {
        $parent = Module::where('code', $subCode)->first();

        if ($parent) {
            foreach ($apps as $index => $app) {
                $fullCode = $subCode . '-' . $app['suffix'];

                // معالجة الأيقونة لضمان التوافق مع مكتبة Heroicons (Outline/Solid)
                $icon = $app['icon'];
                if (!str_starts_with($icon, 'o-') && !str_starts_with($icon, 's-')) {
                    $icon = 'o-' . $icon;
                }

                Module::updateOrCreate(
                    ['code' => $fullCode],
                    [
                        'parent_id'      => $parent->id,
                        'name'           => $app['name'],
                        'type'           => $app['type'] ?? 'app',
                        'icon'           => $icon,
                        'route'          => $app['route'] ?? null, // إضافة حقل المسار
                        'permission_key' => strtolower(str_replace('-', '.', $fullCode)),
                        'sort_order'     => $index + 1,
                        'level'          => 3,
                        'is_active'      => true,
                        'description'    => $app['description'] ?? null,
                        'metadata'       => $app['metadata'] ?? null, // إضافة حقل البيانات الوصفية
                        'settings'       => $app['settings'] ?? null, // إضافة حقل الإعدادات
                    ]
                );
            }
        }
    }
}
