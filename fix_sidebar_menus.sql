-- Add missing Attendance menus
INSERT INTO `sec_menu_item` (`menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES 
('monthly_attendance', 'monthly_manual_attendance', 'attendance', 147, 0, 1, NOW()),
('missing_attendance', 'missing_attendance', 'attendance', 147, 0, 1, NOW()),
('lateness_early_closing', 'lateness_early_closing', 'attendance', 147, 0, 1, NOW()),
('device_connection', 'device_connection', 'attendance', 147, 0, 1, NOW()),
('shift_setup', 'shift_setup', 'attendance', 147, 0, 1, NOW()),
('shift_roster', 'shift_roster', 'attendance', 147, 0, 1, NOW());

-- Add missing Payroll menus
-- Since there is no clear single parent for Payroll level 0, we can use 0 or create one.
-- Looking at existing, level 0 is used for salary_generate, etc.
INSERT INTO `sec_menu_item` (`menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES 
('salary_type_setup', 'create_salary_setup', 'payroll', 0, 0, 1, NOW()),
('salary_setup', 'create_s_setup', 'payroll', 0, 0, 1, NOW());

-- Add missing Expense menus
INSERT INTO `sec_menu_item` (`menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES 
('expense_item', 'expense_item', 'expense', 0, 0, 1, NOW()),
('expense_sheet', 'expense_chart', 'expense', 0, 0, 1, NOW()),
('expense_statement', 'expense_statement_form', 'expense', 0, 0, 1, NOW());

-- Add missing Income menus
INSERT INTO `sec_menu_item` (`menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES 
('income_field', 'income_item', 'income', 0, 0, 1, NOW()),
('income_sheet', 'income_chart', 'income', 0, 0, 1, NOW()),
('income_statement', 'income_statement_form', 'income', 0, 0, 1, NOW());

-- Grant permissions for all newly added menus to Role 1 (Superadmin)
INSERT INTO `sec_role_permission` (`role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`)
SELECT 1, menu_id, 1, 1, 1, 1, 1, NOW()
FROM sec_menu_item
WHERE menu_id NOT IN (SELECT menu_id FROM sec_role_permission WHERE role_id = 1);
