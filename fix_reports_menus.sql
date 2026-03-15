-- Add missing Report menus (Child of employee_reports parent 177)
INSERT INTO `sec_menu_item` (`menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES 
('asset', 'employee_assets', 'reports', 177, 1, 1, NOW()),
('benifit_report', 'benifit_list', 'reports', 177, 1, 1, NOW()),
('custom_report', 'custom_report', 'reports', 177, 1, 1, NOW());

-- Grant permissions for these new report menus to Role 1
INSERT INTO `sec_role_permission` (`role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`)
SELECT 1, menu_id, 1, 1, 1, 1, 1, NOW()
FROM sec_menu_item
WHERE menu_title IN ('asset', 'benifit_report', 'custom_report') 
AND module = 'reports'
AND menu_id NOT IN (SELECT menu_id FROM sec_role_permission WHERE role_id = 1);
