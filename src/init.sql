INSERT INTO `#PREFIX#permissions` ( `id`, `name`, `title`, `route`, `component`, `icon`, `parent_id`, `addon_code`, `guard_name`, `weight`, `note`, `type`, `status`, `is_nav`, `is_inner`, `controller`, `deleted_at`, `created_at`, `updated_at` ) VALUES
('1','console','仪表盘','console',NULL,'layui-icon layui-icon-console','0',NULL,'admin','0',NULL,'nav','1','1','0',NULL,NULL,'1715165941','1715165941'),
('2','user','用户管理',NULL,NULL,'layui-icon layui-icon-table','0',NULL,'admin','0',NULL,'dir','1','1','0',NULL,NULL,'1715165984','1715165984'),
('3','user.users','会员列表','users',NULL,NULL,'2',NULL,'admin','0',NULL,'nav','1','1','0',NULL,NULL,'1715166022','1715166022'),
('4','system','系统管理',NULL,NULL,'layui-icon layui-icon-engine','0',NULL,'admin','0',NULL,'dir','1','1','0',NULL,NULL,'1715166058','1715166058'),
('5','system.role','系统角色','roles',NULL,'pt-UserFilled','4',NULL,'admin','0',NULL,'nav','1','1','0',NULL,NULL,'1715166101','1715166101'),
('6','system.system','系统管理员','systems',NULL,'pt-Avatar','4',NULL,'admin','0',NULL,'nav','1','1','0',NULL,NULL,'1715166143','1715166143'),
('7','system.permissions','菜单栏目','permissions',NULL,'pt-Calendar','4',NULL,'admin','0',NULL,'nav','1','1','0',NULL,NULL,'1715166192','1715166192'),
('8','system.login','登录日志','system/login',NULL,'pt-Bell','4',NULL,'admin','0','查看后端用户的登录日志信息','nav','1','1','0',NULL,NULL,'1715166306','1715166306'),
('9','system.operate','操作日志','operations',NULL,'pt-Calendar','4',NULL,'admin','0','查看后端管理操作日志信息','nav','1','1','0',NULL,NULL,'1715166353','1715251716'),
('10','system.setting','系统配置','settings',NULL,'pt-Menu','4',NULL,'admin','0',NULL,'nav','1','1','0',NULL,NULL,'1715166392','1718361276'),
('11','system.attachments','附件管理','attachments',NULL,NULL,'4',NULL,'admin','0',NULL,'nav','1','1','0',NULL,NULL,'1715166451','1715166610'),
('12','addon','插件管理',NULL,NULL,'layui-icon layui-icon-align-left','0',NULL,'admin','0',NULL,'dir','1','1','0',NULL,NULL,'1715166498','1715166498'),
('13','addon.addons','插件列表','addons',NULL,NULL,'12',NULL,'admin','0',NULL,'nav','1','1','0',NULL,NULL,'1715166540','1715166540');


INSERT INTO `#PREFIX#setting_groups` ( `id`, `title`, `name`, `weight`, `parent_id`, `intro`, `addon_code`, `status`, `deleted_at`, `updated_at`, `created_at` ) VALUES
('1','基础设置','base','99','0',NULL,NULL,'1',NULL,'1701941325','0'),
('2','第三方登录设置','login_set','99','0',NULL,NULL,'1','1702628365','1702628365','0'),
('3','QQ登录','qq_login','99','2',NULL,NULL,'1','1702628350','1702628350','0'),
('4','微信登录','wechat_login','99','2',NULL,NULL,'1','1702628353','1702628353','0'),
('5','站点设置','website','99','1',NULL,NULL,'1',NULL,'0','0'),
('6','第三方登录','oauth','0','0','',NULL,'1',NULL,'1712128576','1712128576'),
('7','QQ登录','qq_login','0','6','qq登录第三方授权',NULL,'1',NULL,'1713854885','1712131033'),
('8','微信登录','wechat_login','0','6','',NULL,'1',NULL,'1712131878','1712131878'),
('9','微博登录','weibo_login','0','6',NULL,NULL,'1',NULL,'1713148999','1712461785');


INSERT INTO `#PREFIX#settings` ( `id`, `title`, `name`, `setting_group_id`, `weight`, `type`, `intro`, `extra`, `value`, `default_val`, `created_at`, `updated_at` ) VALUES
                                                                                                                                                                         ('1','网站标题','title','5','99','text',NULL,NULL,'PTAdmin',NULL,'0','1712459544'),
                                                                                                                                                                         ('2','网站logo','logo','5','99','img',NULL,NULL,'/ptadmin/images/login.png',NULL,'0','1712459544'),
                                                                                                                                                                         ('3','SEO关键词','seo_keyword','5','99','text',NULL,NULL,'PTAdmin快速建站工具，CMS系统',NULL,'0','1712459544'),
                                                                                                                                                                         ('4','SEO描述','seo_description','5','99','textarea',NULL,NULL,'基于laravel+layui的后台管理系统',NULL,'0','1712459544'),
                                                                                                                                                                         ('5','站点状态','website_status','5','100','radio',NULL,'关闭
开启','0','1','0','1712459544'),
                                                                                                                                                                         ('6','AppID','app_id','7','0','text','设置QQ登录AppID','','','','1712131444','1714886752'),
                                                                                                                                                                         ('7','AppSecret','app_secret','7','0','text','设置qq登录密钥信息','','','','1712131783','1714886752'),
                                                                                                                                                                         ('8','AppID','app_id','8','0','text',NULL,NULL,'',NULL,'1712458548','1714886752'),
                                                                                                                                                                         ('9','AppSecret','app_secret','8','0','text',NULL,NULL,'',NULL,'1712458598','1714886752'),
                                                                                                                                                                         ('10','ICON','icon','7','10','img',NULL,NULL,'',NULL,'1712459749','1714886752'),
                                                                                                                                                                         ('11','AppID','app_id','9','0','text',NULL,NULL,'',NULL,'1713148681','1714886752'),
                                                                                                                                                                         ('12','AppSecret','app_secret','9','0','text',NULL,NULL,'',NULL,'1713148706','1714886752'),
                                                                                                                                                                         ('13','是否启用','acvite','7','11','radio',NULL,'关闭
启用','1','1','1713854806','1714886752'),
                                                                                                                                                                         ('14','ICON','icon','8','1','img',NULL,NULL,'',NULL,'1713855623','1714886753'),
                                                                                                                                                                         ('15','是否启用','active','8','2','radio',NULL,'关闭
启用','1','1','1713855659','1714886753'),
                                                                                                                                                                         ('16','ICON','icon','9','1','img',NULL,NULL,'',NULL,'1713855686','1714886753'),
                                                                                                                                                                         ('17','是否启用','active','9','2','radio',NULL,'关闭
启用','1','1','1713855714','1714886753');

