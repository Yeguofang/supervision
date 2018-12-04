# Host: localhost  (Version: 5.5.53)
# Date: 2018-11-29 01:09:00
# Generator: MySQL-Front 5.3  (Build 4.234)

/*!40101 SET NAMES utf8 */;

#
# Structure for table "s_admin"
#

DROP TABLE IF EXISTS `s_admin`;
CREATE TABLE `s_admin` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `username` varchar(20) NOT NULL DEFAULT '' COMMENT '用户名',
  `nickname` varchar(50) NOT NULL DEFAULT '' COMMENT '昵称',
  `password` varchar(32) NOT NULL DEFAULT '' COMMENT '密码',
  `salt` varchar(30) NOT NULL DEFAULT '' COMMENT '密码盐',
  `avatar` varchar(100) NOT NULL DEFAULT '' COMMENT '头像',
  `email` varchar(100) NOT NULL DEFAULT '' COMMENT '电子邮箱',
  `loginfailure` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '失败次数',
  `logintime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '登录时间',
  `createtime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `updatetime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `token` varchar(59) NOT NULL DEFAULT '' COMMENT 'Session标识',
  `status` varchar(30) NOT NULL DEFAULT 'normal' COMMENT '状态',
  `mobile` varchar(20) DEFAULT NULL COMMENT '联系方式',
  `is_law` varchar(2) DEFAULT NULL COMMENT '是否有执法证：1代表有，0代表没有',
  `worker_code` varchar(50) DEFAULT NULL COMMENT '工号',
  `supervisor_card` varchar(255) DEFAULT NULL COMMENT '监督员证',
  `admin_code` varchar(50) DEFAULT NULL COMMENT '编号',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='管理员表';

#
# Data for table "s_admin"
#

INSERT INTO `s_admin` VALUES (1,'admin','Admin','6dc17aaf73f286b3e5c1ca95ac81791c','0ee1ca','/assets/img/avatar.png','admin@admin.com',0,1543417212,1492186163,1543424748,'','normal',NULL,NULL,NULL,NULL,NULL);

#
# Structure for table "s_admin_log"
#

DROP TABLE IF EXISTS `s_admin_log`;
CREATE TABLE `s_admin_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `admin_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '管理员ID',
  `username` varchar(30) NOT NULL DEFAULT '' COMMENT '管理员名字',
  `url` varchar(1500) NOT NULL DEFAULT '' COMMENT '操作页面',
  `title` varchar(100) NOT NULL DEFAULT '' COMMENT '日志标题',
  `content` text NOT NULL COMMENT '内容',
  `ip` varchar(50) NOT NULL DEFAULT '' COMMENT 'IP',
  `useragent` varchar(255) NOT NULL DEFAULT '' COMMENT 'User-Agent',
  `createtime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '操作时间',
  PRIMARY KEY (`id`),
  KEY `name` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='管理员日志表';

#
# Data for table "s_admin_log"
#


#
# Structure for table "s_attachment"
#

DROP TABLE IF EXISTS `s_attachment`;
CREATE TABLE `s_attachment` (
  `id` int(20) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `admin_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '管理员ID',
  `user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '会员ID',
  `url` varchar(255) NOT NULL DEFAULT '' COMMENT '物理路径',
  `imagewidth` varchar(30) NOT NULL DEFAULT '' COMMENT '宽度',
  `imageheight` varchar(30) NOT NULL DEFAULT '' COMMENT '高度',
  `imagetype` varchar(30) NOT NULL DEFAULT '' COMMENT '图片类型',
  `imageframes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '图片帧数',
  `filesize` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '文件大小',
  `mimetype` varchar(100) NOT NULL DEFAULT '' COMMENT 'mime类型',
  `extparam` varchar(255) NOT NULL DEFAULT '' COMMENT '透传数据',
  `createtime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建日期',
  `updatetime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `uploadtime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上传时间',
  `storage` varchar(100) NOT NULL DEFAULT 'local' COMMENT '存储位置',
  `sha1` varchar(40) NOT NULL DEFAULT '' COMMENT '文件 sha1编码',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='附件表';

#
# Data for table "s_attachment"
#

INSERT INTO `s_attachment` VALUES (1,1,0,'/assets/img/qrcode.png','150','150','png',0,21859,'image/png','',1499681848,1499681848,1499681848,'local','17163603d0263e4838b9387ff2cd4877e8b018f6'),(2,10,0,'/uploads/20181126/a2b264126247ca78afe84cfbbaaa4082.jpg','4160','3120','jpg',0,1439213,'image/jpeg','',1543228425,1543228425,1543228425,'local','c1a96cc40eaa20d1add8ed29bf80db6f65b24b70'),(3,10,0,'/uploads/20181128/a2b264126247ca78afe84cfbbaaa4082.jpg','4160','3120','jpg',0,1439213,'image/jpeg','',1543368571,1543368571,1543368571,'local','c1a96cc40eaa20d1add8ed29bf80db6f65b24b70');

#
# Structure for table "s_auth_group"
#

DROP TABLE IF EXISTS `s_auth_group`;
CREATE TABLE `s_auth_group` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '父组别',
  `name` varchar(100) NOT NULL DEFAULT '' COMMENT '组名',
  `rules` text NOT NULL COMMENT '规则ID',
  `createtime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `updatetime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` varchar(30) NOT NULL DEFAULT '' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='分组表';

#
# Data for table "s_auth_group"
#

INSERT INTO `s_auth_group` VALUES (1,0,'Admin group','*',1490883540,149088354,'normal'),(6,1,'质监站','67,68,69,70,71,72,79,82,83,84,85,78',1542957356,1543329497,'normal'),(7,1,'安监站','70,71,72,80,83,85,78',1542957368,1543329533,'normal'),(8,1,'行政审批股','66',1542957373,1543227562,'normal'),(9,1,'建筑业管理股','81,78',1542957391,1543285214,'normal'),(10,6,'质监站长','67,79,84,78',1542957411,1543330227,'normal'),(11,6,'质监副站长','68,79,84,78',1542957478,1543330234,'normal'),(12,6,'质监员','69,79,84,78',1542957597,1543330242,'normal'),(13,6,'质监资料录入员','82,84',1542958104,1543330329,'normal'),(14,7,'安监站长','70,80,83,78',1542958120,1543330129,'normal'),(15,7,'安监副站长','71,80,83,78',1542958128,1543330186,'normal'),(16,7,'安监员','72,80,83,78',1542958311,1543330199,'normal'),(17,7,'安监资料录入员','83,85',1542958346,1543330211,'normal');

#
# Structure for table "s_auth_group_access"
#

DROP TABLE IF EXISTS `s_auth_group_access`;
CREATE TABLE `s_auth_group_access` (
  `uid` int(10) unsigned NOT NULL COMMENT '会员ID',
  `group_id` int(10) unsigned NOT NULL COMMENT '级别ID',
  UNIQUE KEY `uid_group_id` (`uid`,`group_id`),
  KEY `uid` (`uid`),
  KEY `group_id` (`group_id`),
  CONSTRAINT `s_auth_group_access_ibfk_1` FOREIGN KEY (`uid`) REFERENCES `s_admin` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='权限分组表';

#
# Data for table "s_auth_group_access"
#

INSERT INTO `s_auth_group_access` VALUES (1,1);

#
# Structure for table "s_auth_rule"
#

DROP TABLE IF EXISTS `s_auth_rule`;
CREATE TABLE `s_auth_rule` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` enum('menu','file') NOT NULL DEFAULT 'file' COMMENT 'menu为菜单,file为权限节点',
  `pid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '父ID',
  `name` varchar(100) NOT NULL DEFAULT '' COMMENT '规则名称',
  `title` varchar(50) NOT NULL DEFAULT '' COMMENT '规则名称',
  `icon` varchar(50) NOT NULL DEFAULT '' COMMENT '图标',
  `condition` varchar(255) NOT NULL DEFAULT '' COMMENT '条件',
  `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
  `ismenu` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否为菜单',
  `createtime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `updatetime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `weigh` int(10) NOT NULL DEFAULT '0' COMMENT '权重',
  `status` varchar(30) NOT NULL DEFAULT '' COMMENT '状态',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`) USING BTREE,
  KEY `pid` (`pid`),
  KEY `weigh` (`weigh`)
) ENGINE=InnoDB AUTO_INCREMENT=86 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='节点表';

#
# Data for table "s_auth_rule"
#

INSERT INTO `s_auth_rule` VALUES (2,'file',0,'general','General','fa fa-cogs','','',0,1497429920,1542959752,137,'normal'),(5,'file',0,'auth','Auth','fa fa-group','','',1,1497429920,1497430092,0,'normal'),(8,'file',2,'general/profile','Profile','fa fa-user','','',0,1497429920,1542959750,34,'normal'),(9,'file',5,'auth/admin','Admin','fa fa-user','','Admin tips',1,1497429920,1497430320,118,'normal'),(10,'file',5,'auth/adminlog','Admin log','fa fa-list-alt','','Admin log tips',1,1497429920,1497430307,113,'normal'),(11,'file',5,'auth/group','Group','fa fa-group','','Group tips',1,1497429920,1497429920,109,'normal'),(12,'file',5,'auth/rule','Rule','fa fa-bars','','Rule tips',1,1497429920,1497430581,104,'normal'),(29,'file',8,'general/profile/index','View','fa fa-circle-o','','',0,1497429920,1497429920,33,'normal'),(30,'file',8,'general/profile/update','Update profile','fa fa-circle-o','','',0,1497429920,1497429920,32,'normal'),(31,'file',8,'general/profile/add','Add','fa fa-circle-o','','',0,1497429920,1497429920,31,'normal'),(32,'file',8,'general/profile/edit','Edit','fa fa-circle-o','','',0,1497429920,1497429920,30,'normal'),(33,'file',8,'general/profile/del','Delete','fa fa-circle-o','','',0,1497429920,1497429920,29,'normal'),(34,'file',8,'general/profile/multi','Multi','fa fa-circle-o','','',0,1497429920,1497429920,28,'normal'),(40,'file',9,'auth/admin/index','View','fa fa-circle-o','','Admin tips',0,1497429920,1497429920,117,'normal'),(41,'file',9,'auth/admin/add','Add','fa fa-circle-o','','',0,1497429920,1497429920,116,'normal'),(42,'file',9,'auth/admin/edit','Edit','fa fa-circle-o','','',0,1497429920,1497429920,115,'normal'),(43,'file',9,'auth/admin/del','Delete','fa fa-circle-o','','',0,1497429920,1497429920,114,'normal'),(44,'file',10,'auth/adminlog/index','View','fa fa-circle-o','','Admin log tips',0,1497429920,1497429920,112,'normal'),(45,'file',10,'auth/adminlog/detail','Detail','fa fa-circle-o','','',0,1497429920,1497429920,111,'normal'),(46,'file',10,'auth/adminlog/del','Delete','fa fa-circle-o','','',0,1497429920,1497429920,110,'normal'),(47,'file',11,'auth/group/index','View','fa fa-circle-o','','Group tips',0,1497429920,1497429920,108,'normal'),(48,'file',11,'auth/group/add','Add','fa fa-circle-o','','',0,1497429920,1497429920,107,'normal'),(49,'file',11,'auth/group/edit','Edit','fa fa-circle-o','','',0,1497429920,1497429920,106,'normal'),(50,'file',11,'auth/group/del','Delete','fa fa-circle-o','','',0,1497429920,1497429920,105,'normal'),(51,'file',12,'auth/rule/index','View','fa fa-circle-o','','Rule tips',0,1497429920,1497429920,103,'normal'),(52,'file',12,'auth/rule/add','Add','fa fa-circle-o','','',0,1497429920,1497429920,102,'normal'),(53,'file',12,'auth/rule/edit','Edit','fa fa-circle-o','','',0,1497429920,1497429920,101,'normal'),(54,'file',12,'auth/rule/del','Delete','fa fa-circle-o','','',0,1497429920,1497429920,100,'normal'),(66,'file',0,'administration/project/index','行政建档','fa fa-copy','','',1,1543149290,1543150012,130,'normal'),(67,'file',84,'quality/master/index','站长项目管理','fa fa-file-o','','',1,1543209688,1543305151,0,'normal'),(68,'file',84,'quality/assistant/index','副站长项目管理','fa fa-paste','','',1,1543216536,1543305158,0,'normal'),(69,'file',84,'quality/chief/index','质监员项目管理','fa fa-files-o','','',1,1543221033,1543305165,0,'normal'),(70,'file',83,'safety/master/index','站长项目管理','fa fa-align-center','','',1,1543226753,1543305171,0,'normal'),(71,'file',83,'safety/assistant/index','副站长项目管理','fa fa-align-justify','','',1,1543226780,1543305178,0,'normal'),(72,'file',83,'safety/chief/index','安监员项目管理','fa fa-align-left','','',1,1543226805,1543305185,0,'normal'),(73,'file',0,'dept','部门管理','fa fa-user-o','','',1,1543229434,1543229434,0,'normal'),(74,'file',73,'dept/administration','行政管理','fa fa-paypal','','',1,1543229500,1543229500,0,'normal'),(75,'file',73,'dept/build','建筑业管理股管理','fa fa-institution','','',1,1543229572,1543229572,0,'normal'),(76,'file',73,'dept/quality','质监部管理','fa fa-database','','',1,1543229598,1543229598,0,'normal'),(77,'file',73,'dept/safety','安监部管理','fa fa-shield','','',1,1543229636,1543229636,0,'normal'),(78,'file',0,'check','验收管理','fa fa-pencil-square-o','','',1,1543285033,1543285033,0,'normal'),(79,'file',78,'check/quality','质监站验收','fa fa-gittip','','',1,1543285071,1543285071,0,'normal'),(80,'file',78,'check/safety','安监站验收','fa fa-heart','','',1,1543285167,1543285167,0,'normal'),(81,'file',78,'check/build','建管验收列表','fa fa-bell','','',1,1543285185,1543285185,0,'normal'),(82,'file',84,'quality/info/index','质监录入列表','fa fa-pencil','','',1,1543293055,1543305285,0,'normal'),(83,'file',0,'safety','安监项目管理','fa fa-laptop','','',1,1543305095,1543331252,148,'normal'),(84,'file',0,'quality','质监员项目管理','fa fa-minus-square','','',1,1543305125,1543331245,150,'normal'),(85,'file',83,'safety/info/index','安监资料录入员','fa fa-pencil-square','','',1,1543322469,1543322469,0,'normal');

#
# Structure for table "s_change_person_log"
#

DROP TABLE IF EXISTS `s_change_person_log`;
CREATE TABLE `s_change_person_log` (
  `Id` varchar(32) NOT NULL DEFAULT '',
  `project_id` int(11) unsigned DEFAULT NULL,
  `person_type` varchar(2) CHARACTER SET utf8mb4 DEFAULT NULL COMMENT '1代表质监主责，2代表安监主责，3代表质监员,4安监员,5质监副站，6安监副站',
  `before_person` varchar(255) CHARACTER SET utf8mb4 DEFAULT NULL COMMENT '更改前的人',
  `after_person` varchar(255) CHARACTER SET utf8mb4 DEFAULT NULL,
  `extra_id` varchar(255) CHARACTER SET utf8mb4 DEFAULT NULL,
  `create_time` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='人员修改表';

#
# Data for table "s_change_person_log"
#


#
# Structure for table "s_config"
#

DROP TABLE IF EXISTS `s_config`;
CREATE TABLE `s_config` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL DEFAULT '' COMMENT '变量名',
  `group` varchar(30) NOT NULL DEFAULT '' COMMENT '分组',
  `title` varchar(100) NOT NULL DEFAULT '' COMMENT '变量标题',
  `tip` varchar(100) NOT NULL DEFAULT '' COMMENT '变量描述',
  `type` varchar(30) NOT NULL DEFAULT '' COMMENT '类型:string,text,int,bool,array,datetime,date,file',
  `value` text NOT NULL COMMENT '变量值',
  `content` text NOT NULL COMMENT '变量字典数据',
  `rule` varchar(100) NOT NULL DEFAULT '' COMMENT '验证规则',
  `extend` varchar(255) NOT NULL DEFAULT '' COMMENT '扩展属性',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='系统配置';

#
# Data for table "s_config"
#

INSERT INTO `s_config` VALUES (1,'name','basic','Site name','请填写站点名称','string','质量安全监督管理系统','','required',''),(2,'beian','basic','Beian','粤ICP备15054802号-4','string','','','',''),(3,'cdnurl','basic','Cdn url','如果静态资源使用第三方云储存请配置该值','string','','','',''),(4,'version','basic','Version','如果静态资源有变动请重新配置该值','string','1.0.1','','required',''),(5,'timezone','basic','Timezone','','string','Asia/Shanghai','','required',''),(6,'forbiddenip','basic','Forbidden ip','一行一条记录','text','','','',''),(7,'languages','basic','Languages','','array','{\"backend\":\"zh-cn\",\"frontend\":\"zh-cn\"}','','required',''),(8,'fixedpage','basic','Fixed page','请尽量输入左侧菜单栏存在的链接','string','dashboard','','required',''),(9,'categorytype','dictionary','Category type','','array','{\"default\":\"Default\",\"page\":\"Page\",\"article\":\"Article\",\"test\":\"Test\"}','','',''),(10,'configgroup','dictionary','Config group','','array','{\"basic\":\"Basic\",\"email\":\"Email\",\"dictionary\":\"Dictionary\",\"user\":\"User\",\"example\":\"Example\"}','','',''),(11,'mail_type','email','Mail type','选择邮件发送方式','select','1','[\"Please select\",\"SMTP\",\"Mail\"]','',''),(12,'mail_smtp_host','email','Mail smtp host','错误的配置发送邮件会导致服务器超时','string','smtp.qq.com','','',''),(13,'mail_smtp_port','email','Mail smtp port','(不加密默认25,SSL默认465,TLS默认587)','string','465','','',''),(14,'mail_smtp_user','email','Mail smtp user','（填写完整用户名）','string','10000','','',''),(15,'mail_smtp_pass','email','Mail smtp password','（填写您的密码）','string','password','','',''),(16,'mail_verify_type','email','Mail vertify type','（SMTP验证方式[推荐SSL]）','select','2','[\"None\",\"TLS\",\"SSL\"]','',''),(17,'mail_from','email','Mail from','','string','10000@qq.com','','','');

#
# Structure for table "s_five_change"
#

DROP TABLE IF EXISTS `s_five_change`;
CREATE TABLE `s_five_change` (
  `id` varchar(32) NOT NULL DEFAULT '',
  `kind` varchar(1) DEFAULT NULL COMMENT '0、建设1、设计2、勘察3、施工4、监理',
  `type` varchar(1) DEFAULT NULL COMMENT '0、公司1、人',
  `before_person` varchar(255) DEFAULT NULL COMMENT '更改前的人',
  `after_person` varchar(255) DEFAULT NULL,
  `extra_id` varchar(255) DEFAULT NULL COMMENT '更改的人的admin_id',
  `create_time` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='五大责任主体变更表';

#
# Data for table "s_five_change"
#


#
# Structure for table "s_licence"
#

DROP TABLE IF EXISTS `s_licence`;
CREATE TABLE `s_licence` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `licence_code` varchar(255) DEFAULT NULL COMMENT '编码',
  `qr_code` varchar(255) DEFAULT NULL COMMENT '二维码',
  `area` varchar(100) CHARACTER SET utf8 DEFAULT NULL COMMENT '建设规模（单位平方米）',
  `cost` varchar(255) CHARACTER SET utf8 DEFAULT NULL COMMENT '合同价格（单位万元）',
  `design_company` varchar(255) CHARACTER SET utf8 DEFAULT NULL COMMENT '设计单位',
  `design_person` varchar(255) CHARACTER SET utf8 DEFAULT NULL COMMENT '设计负责人',
  `survey_company` varchar(255) CHARACTER SET utf8 DEFAULT NULL COMMENT '勘察单位',
  `survey_person` varchar(255) CHARACTER SET utf8 DEFAULT NULL COMMENT '勘察负责人',
  `construction_company` varchar(255) CHARACTER SET utf8 DEFAULT NULL COMMENT '施工单位',
  `construction_person` varchar(255) CHARACTER SET utf8 DEFAULT NULL COMMENT '施工负责人',
  `supervision_company` varchar(255) CHARACTER SET utf8 DEFAULT NULL COMMENT '监理单位',
  `supervision_person` varchar(255) CHARACTER SET utf8 DEFAULT NULL COMMENT '监理负责人',
  `begin_time` int(10) DEFAULT NULL COMMENT '合同工期开始时间',
  `end_time` int(10) DEFAULT NULL COMMENT '合同工期结束时间',
  `remark` text COMMENT '备注',
  `create_time` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT COMMENT='许可证书表';

#
# Data for table "s_licence"
#


#
# Structure for table "s_person_project"
#

DROP TABLE IF EXISTS `s_person_project`;
CREATE TABLE `s_person_project` (
  `id` varchar(32) NOT NULL DEFAULT '',
  `admin_id` int(11) unsigned NOT NULL DEFAULT '0',
  `project_id` int(11) unsigned NOT NULL DEFAULT '0',
  `create_time` int(10) unsigned DEFAULT NULL,
  `type` varchar(1) DEFAULT '1' COMMENT '1代表质监员，2代表安监员。3监理人员。4、施工人员。5验收人员。6检查人员',
  `kind` varchar(1) DEFAULT NULL COMMENT '只有type3-6有，0，代表总负责人，验收那里代表验收等级。1、代表下面的人员',
  `extra` varchar(255) DEFAULT NULL COMMENT '当type=3-4时是人是手输的、',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT COMMENT='质监局人员项目管理表';

#
# Data for table "s_person_project"
#


#
# Structure for table "s_project"
#

DROP TABLE IF EXISTS `s_project`;
CREATE TABLE `s_project` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `admin_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建管理员id',
  `build_dept` varchar(255) DEFAULT NULL COMMENT '建设单位',
  `project_name` varchar(255) DEFAULT NULL COMMENT '工程名称',
  `address` varchar(255) DEFAULT NULL COMMENT '地址',
  `licence_id` int(11) unsigned DEFAULT NULL COMMENT '许可证id',
  `register_time` int(10) DEFAULT NULL COMMENT '监督注册表审批时间',
  `permit_time` int(10) DEFAULT NULL COMMENT '施工许可审批时间',
  `begin_time` int(10) DEFAULT NULL COMMENT '开工时间',
  `check_time` int(10) DEFAULT NULL COMMENT '验收时间',
  `end_time` int(10) DEFAULT NULL COMMENT '完工日期',
  `record_time` int(10) unsigned DEFAULT NULL COMMENT '备案时间',
  `finish_time` int(10) unsigned DEFAULT NULL COMMENT '竣工日期',
  `supervise_time` int(10) unsigned DEFAULT NULL COMMENT '报监日期',
  `push_time` int(10) DEFAULT NULL COMMENT '报建日期',
  `quality_id` int(10) unsigned DEFAULT NULL COMMENT '质检员id',
  `security_id` int(10) unsigned DEFAULT NULL COMMENT '安监员id',
  `quality_assistant` int(11) DEFAULT NULL COMMENT '质监副站长id',
  `supervisor_assistant` int(11) DEFAULT NULL COMMENT '安监副站长id',
  `quality_info` int(11) DEFAULT NULL COMMENT '质监资料id',
  `safety_info` int(11) DEFAULT NULL COMMENT '安监资料id',
  `quality_code` varchar(255) DEFAULT NULL COMMENT '质监告知书编号',
  `supervisor_code` varchar(255) DEFAULT NULL COMMENT '安监告知书编号',
  `supervisor_progress` varchar(255) NOT NULL DEFAULT '0' COMMENT '安监进度 0、未处理，1、已申请中止施工并已通知副站。2、已通知站长 3、同意',
  `quality_progress` varchar(255) NOT NULL DEFAULT '0' COMMENT '质监进度 0、未处理，1、已申请竣工并已通知副站。2、已通知站长3、同意',
  `recode_status` varchar(1) DEFAULT '0' COMMENT '0代表未备案，1代表已备案',
  `supervisory_report` varchar(255) DEFAULT '1' COMMENT '监督报告 1、未提交。2、已交',
  `build_check` varchar(1) NOT NULL DEFAULT '0' COMMENT '0、未处理。1、同意',
  `supervisor_time` int(10) DEFAULT NULL COMMENT '安监下发告知书时间',
  `quality_time` int(10) DEFAULT NULL COMMENT '质监下发告知书时间',
  `create_time` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='项目表';

#
# Data for table "s_project"
#


#
# Structure for table "s_quality_info"
#

DROP TABLE IF EXISTS `s_quality_info`;
CREATE TABLE `s_quality_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `picture_company` varchar(255) DEFAULT NULL COMMENT '图审机构公司名',
  `picture_person` varchar(255) DEFAULT NULL COMMENT '图审机构联系人',
  `build_person` varchar(255) DEFAULT NULL COMMENT '建设负责人',
  `project_kind` varchar(1) DEFAULT NULL COMMENT '工程类别0，市政建设。1,房建',
  `energy` varchar(255) DEFAULT NULL COMMENT '节能',
  `extend` varchar(255) DEFAULT NULL COMMENT '道路工程延长米',
  `structure` varchar(255) DEFAULT NULL COMMENT '结构形式',
  `floor` varchar(255) NOT NULL DEFAULT ',' COMMENT '层数（地上,地下）',
  `schedule` varchar(255) NOT NULL DEFAULT '/' COMMENT '工程进度 (分子代表进度，分母代表总数 如:1/22)',
  `situation` varchar(255) DEFAULT NULL COMMENT '工程概况(当project_kind为0的时候 0代表路基处理，1路面工程，2排水系统，3绿化照明4标识标线5完成6竣工验收)（当kind为1时0基础阶段，1主体阶段，2装饰阶段3、收尾4、完工5、竣工验收）',
  `status_extra` varchar(255) DEFAULT NULL COMMENT '当situation为主体阶段的时候即kind=1situation=1时,逗号前0代表框架1，代表砌砖。逗号后代表层数。当kind=1situation=2时0代表水电安装，1代表普通装饰',
  `check_company` varchar(255) DEFAULT NULL COMMENT '检测公司',
  `check_person` varchar(255) DEFAULT NULL COMMENT '检测负责人',
  `status` varchar(1) NOT NULL DEFAULT '0' COMMENT '0、未开工1、在建2、质量停工3、安全停工4、局停工5、自停工',
  `create_time` int(10) unsigned DEFAULT NULL,
  `inform_time` int(10) DEFAULT NULL COMMENT '告知书日期',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='安监资料';

#
# Data for table "s_quality_info"
#


#
# Structure for table "s_safety_info"
#

DROP TABLE IF EXISTS `s_safety_info`;
CREATE TABLE `s_safety_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `safety_code` varchar(255) DEFAULT NULL COMMENT '安全监督编号',
  `down_time` int(10) DEFAULT NULL COMMENT '开工交底',
  `stop_time` int(10) DEFAULT NULL COMMENT '中止施工日期',
  `assess_time` int(10) DEFAULT NULL COMMENT '评定办结日期',
  `argument_time` int(10) DEFAULT NULL COMMENT '论证日期',
  `inspect_time` int(10) DEFAULT NULL COMMENT '检查日期',
  `reply_time` int(10) DEFAULT NULL COMMENT '回复日期',
  `assess` text COMMENT '工程评定结论',
  `danger` varchar(255) DEFAULT NULL COMMENT '危险性较大的分部分项工程范围',
  `scale_danger` varchar(255) DEFAULT NULL COMMENT '超过一定规模的危险性较大的分部分项工程范围',
  `measure` varchar(255) DEFAULT NULL COMMENT '整改措施0、抽查1、整改、2、暂停3、约谈4、扣分',
  `content` varchar(255) DEFAULT NULL COMMENT '整改内容',
  `deadline` varchar(255) DEFAULT NULL COMMENT '整改期限',
  `document_code` varchar(255) DEFAULT NULL COMMENT '文书编号',
  `remark` text COMMENT '备注',
  `create_time` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='安监资料';

#
# Data for table "s_safety_info"
#


#
# Structure for table "s_sms"
#

DROP TABLE IF EXISTS `s_sms`;
CREATE TABLE `s_sms` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `event` varchar(30) NOT NULL DEFAULT '' COMMENT '事件',
  `mobile` varchar(20) NOT NULL DEFAULT '' COMMENT '手机号',
  `code` varchar(10) NOT NULL DEFAULT '' COMMENT '验证码',
  `times` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '验证次数',
  `ip` varchar(30) NOT NULL DEFAULT '' COMMENT 'IP',
  `createtime` int(10) unsigned DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='短信验证码表';

#
# Data for table "s_sms"
#


#
# Structure for table "s_user"
#

DROP TABLE IF EXISTS `s_user`;
CREATE TABLE `s_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `group_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '组别ID',
  `username` varchar(32) NOT NULL DEFAULT '' COMMENT '用户名',
  `nickname` varchar(50) NOT NULL DEFAULT '' COMMENT '昵称',
  `password` varchar(32) NOT NULL DEFAULT '' COMMENT '密码',
  `salt` varchar(30) NOT NULL DEFAULT '' COMMENT '密码盐',
  `email` varchar(100) NOT NULL DEFAULT '' COMMENT '电子邮箱',
  `mobile` varchar(11) NOT NULL DEFAULT '' COMMENT '手机号',
  `avatar` varchar(255) NOT NULL DEFAULT '' COMMENT '头像',
  `level` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '等级',
  `gender` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '性别',
  `birthday` date DEFAULT NULL COMMENT '生日',
  `bio` varchar(100) NOT NULL DEFAULT '' COMMENT '格言',
  `score` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '积分',
  `successions` int(10) unsigned NOT NULL DEFAULT '1' COMMENT '连续登录天数',
  `maxsuccessions` int(10) unsigned NOT NULL DEFAULT '1' COMMENT '最大连续登录天数',
  `prevtime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上次登录时间',
  `logintime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '登录时间',
  `loginip` varchar(50) NOT NULL DEFAULT '' COMMENT '登录IP',
  `loginfailure` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '失败次数',
  `joinip` varchar(50) NOT NULL DEFAULT '' COMMENT '加入IP',
  `jointime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '加入时间',
  `createtime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `updatetime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `token` varchar(50) NOT NULL DEFAULT '' COMMENT 'Token',
  `status` varchar(30) NOT NULL DEFAULT '' COMMENT '状态',
  `verification` varchar(255) NOT NULL DEFAULT '' COMMENT '验证',
  `identity` varchar(1) DEFAULT NULL COMMENT '身份0、质监1、安监',
  `identity_type` varchar(1) DEFAULT NULL COMMENT '0、站长，1、副站长，2、普通',
  `admin_id` int(11) DEFAULT NULL COMMENT '对应的管理员id',
  PRIMARY KEY (`id`),
  KEY `username` (`username`),
  KEY `email` (`email`),
  KEY `mobile` (`mobile`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='会员表';

#
# Data for table "s_user"
#


#
# Structure for table "s_user_group"
#

DROP TABLE IF EXISTS `s_user_group`;
CREATE TABLE `s_user_group` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT '' COMMENT '组名',
  `rules` text COMMENT '权限节点',
  `createtime` int(10) DEFAULT NULL COMMENT '添加时间',
  `updatetime` int(10) DEFAULT NULL COMMENT '更新时间',
  `status` enum('normal','hidden') DEFAULT NULL COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='会员组表';

#
# Data for table "s_user_group"
#

INSERT INTO `s_user_group` VALUES (1,'默认组','1,2,3,4,5,6,7,8,9,10,11,12',1515386468,1516168298,'normal');

#
# Structure for table "s_user_rule"
#

DROP TABLE IF EXISTS `s_user_rule`;
CREATE TABLE `s_user_rule` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(10) DEFAULT NULL COMMENT '父ID',
  `name` varchar(50) DEFAULT NULL COMMENT '名称',
  `title` varchar(50) DEFAULT '' COMMENT '标题',
  `remark` varchar(100) DEFAULT NULL COMMENT '备注',
  `ismenu` tinyint(1) DEFAULT NULL COMMENT '是否菜单',
  `createtime` int(10) DEFAULT NULL COMMENT '创建时间',
  `updatetime` int(10) DEFAULT NULL COMMENT '更新时间',
  `weigh` int(10) DEFAULT '0' COMMENT '权重',
  `status` enum('normal','hidden') DEFAULT NULL COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='会员规则表';

#
# Data for table "s_user_rule"
#

INSERT INTO `s_user_rule` VALUES (1,0,'index','前台','',1,1516168079,1516168079,1,'normal'),(2,0,'api','API接口','',1,1516168062,1516168062,2,'normal'),(3,1,'user','会员模块','',1,1515386221,1516168103,12,'normal'),(4,2,'user','会员模块','',1,1515386221,1516168092,11,'normal'),(5,3,'index/user/login','登录','',0,1515386247,1515386247,5,'normal'),(6,3,'index/user/register','注册','',0,1515386262,1516015236,7,'normal'),(7,3,'index/user/index','会员中心','',0,1516015012,1516015012,9,'normal'),(8,3,'index/user/profile','个人资料','',0,1516015012,1516015012,4,'normal'),(9,4,'api/user/login','登录','',0,1515386247,1515386247,6,'normal'),(10,4,'api/user/register','注册','',0,1515386262,1516015236,8,'normal'),(11,4,'api/user/index','会员中心','',0,1516015012,1516015012,10,'normal'),(12,4,'api/user/profile','个人资料','',0,1516015012,1516015012,3,'normal');

#
# Structure for table "s_user_score_log"
#

DROP TABLE IF EXISTS `s_user_score_log`;
CREATE TABLE `s_user_score_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '会员ID',
  `score` int(10) NOT NULL DEFAULT '0' COMMENT '变更积分',
  `before` int(10) NOT NULL DEFAULT '0' COMMENT '变更前积分',
  `after` int(10) NOT NULL DEFAULT '0' COMMENT '变更后积分',
  `memo` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
  `createtime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='会员积分变动表';

#
# Data for table "s_user_score_log"
#


#
# Structure for table "s_user_token"
#

DROP TABLE IF EXISTS `s_user_token`;
CREATE TABLE `s_user_token` (
  `token` varchar(50) NOT NULL COMMENT 'Token',
  `user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '会员ID',
  `createtime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `expiretime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '过期时间',
  PRIMARY KEY (`token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='会员Token表';

#
# Data for table "s_user_token"
#

