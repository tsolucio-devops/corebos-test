<?php
/*************************************************************************************************
 * Copyright 2016 JPL TSolucio, S.L. -- This file is a part of TSOLUCIO coreBOS Tests.
 * The MIT License (MIT)
 * Permission is hereby granted, free of charge, to any person obtaining a copy of this software
 * and associated documentation files (the "Software"), to deal in the Software without restriction,
 * including without limitation the rights to use, copy, modify, merge, publish, distribute,
 * sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all copies or
 * substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT
 * NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.
 * IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY,
 * WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE
 * SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 *************************************************************************************************/
use PHPUnit\Framework\TestCase;

class VtigerModuleOperation_QueryTest extends TestCase {

	public static $vtModuleOperation = null;

	private function sortQueryColumns($query) {
		$cols = trim(substr($query, 7, stripos($query, ' from ')-7));
		$cols = explode(',', $cols);
		$cols = array_map('trim', $cols);
		sort($cols);
		$cols = implode(',', $cols);
		return substr($query, 0, 6).' '.$cols.substr($query, stripos($query, ' from '));
	}

	public static function setUpBeforeClass() {
		global $adb, $current_user, $log;
		$current_user = Users::getActiveAdminUser();
		$webserviceObject = VtigerWebserviceObject::fromName($adb, 'Accounts');
		self::$vtModuleOperation = new VtigerModuleOperation($webserviceObject, $current_user, $adb, $log);
	}

	public function testStandardVTQL() {
		$actual = self::$vtModuleOperation->wsVTQL2SQL('select firstname, lastname from Leads order by firstname desc limit 0,2;', $meta, $queryRelatedModules);
		$this->assertEquals("SELECT vtiger_leaddetails.firstname,vtiger_leaddetails.lastname,vtiger_leaddetails.leadid FROM vtiger_leaddetails LEFT JOIN vtiger_crmentity ON vtiger_leaddetails.leadid=vtiger_crmentity.crmid   WHERE  vtiger_crmentity.deleted=0 and vtiger_leaddetails.converted=0 ORDER BY vtiger_leaddetails.firstname DESC LIMIT 0,2;", $actual);
		$actual = self::$vtModuleOperation->wsVTQL2SQL('select accountname from Accounts;', $meta, $queryRelatedModules);
		$this->assertEquals("SELECT vtiger_account.accountname,vtiger_account.accountid FROM vtiger_account LEFT JOIN vtiger_crmentity ON vtiger_account.accountid=vtiger_crmentity.crmid   WHERE  vtiger_crmentity.deleted=0 LIMIT 100;", $actual);
	}

	public function testInventoryModules() {
		$actual = self::$vtModuleOperation->wsVTQL2SQL('select subject from SalesOrder;', $meta, $queryRelatedModules);
		$this->assertEquals("SELECT vtiger_salesorder.subject,vtiger_salesorder.salesorderid FROM vtiger_salesorder LEFT JOIN vtiger_crmentity ON vtiger_salesorder.salesorderid=vtiger_crmentity.crmid   WHERE  vtiger_crmentity.deleted=0 LIMIT 100;", $actual);
		$actual = self::$vtModuleOperation->wsVTQL2SQL('select subject from Quotes;', $meta, $queryRelatedModules);
		$this->assertEquals("SELECT vtiger_quotes.subject,vtiger_quotes.quoteid FROM vtiger_quotes LEFT JOIN vtiger_crmentity ON vtiger_quotes.quoteid=vtiger_crmentity.crmid   WHERE  vtiger_crmentity.deleted=0 LIMIT 100;", $actual);
		$actual = self::$vtModuleOperation->wsVTQL2SQL('select subject from PurchaseOrder;', $meta, $queryRelatedModules);
		$this->assertEquals("SELECT vtiger_purchaseorder.subject,vtiger_purchaseorder.purchaseorderid FROM vtiger_purchaseorder LEFT JOIN vtiger_crmentity ON vtiger_purchaseorder.purchaseorderid=vtiger_crmentity.crmid   WHERE  vtiger_crmentity.deleted=0 LIMIT 100;", $actual);
		$actual = self::$vtModuleOperation->wsVTQL2SQL('select subject from Invoice;', $meta, $queryRelatedModules);
		$this->assertEquals("SELECT vtiger_invoice.subject,vtiger_invoice.invoiceid FROM vtiger_invoice LEFT JOIN vtiger_crmentity ON vtiger_invoice.invoiceid=vtiger_crmentity.crmid   WHERE  vtiger_crmentity.deleted=0 LIMIT 100;", $actual);
		$actual = self::$vtModuleOperation->wsVTQL2SQL('select * from SalesOrder;', $meta, $queryRelatedModules);
		$this->assertEquals("SELECT vtiger_salesorder.subject,vtiger_salesorder.potentialid,vtiger_salesorder.customerno,vtiger_salesorder.salesorder_no,vtiger_salesorder.quoteid,vtiger_salesorder.purchaseorder,vtiger_salesorder.contactid,vtiger_salesorder.duedate,vtiger_salesorder.carrier,vtiger_salesorder.pending,vtiger_salesorder.sostatus,vtiger_salesorder.adjustment,vtiger_salesorder.salescommission,vtiger_salesorder.exciseduty,vtiger_salesorder.total,vtiger_salesorder.subtotal,vtiger_salesorder.taxtype,vtiger_salesorder.discount_percent,vtiger_salesorder.discount_amount,vtiger_salesorder.s_h_amount,vtiger_salesorder.accountid,vtiger_crmentity.smownerid,vtiger_crmentity.createdtime,vtiger_crmentity.modifiedtime,vtiger_salesorder.currency_id,vtiger_salesorder.conversion_rate,vtiger_crmentity.modifiedby,vtiger_crmentity.smcreatorid,vtiger_salesorder.enable_recurring,vtiger_invoice_recurring_info.recurring_frequency,vtiger_invoice_recurring_info.start_period,vtiger_invoice_recurring_info.end_period,vtiger_invoice_recurring_info.payment_duration,vtiger_invoice_recurring_info.invoice_status,vtiger_sobillads.bill_street,vtiger_soshipads.ship_street,vtiger_sobillads.bill_pobox,vtiger_soshipads.ship_pobox,vtiger_sobillads.bill_city,vtiger_soshipads.ship_city,vtiger_sobillads.bill_state,vtiger_soshipads.ship_state,vtiger_sobillads.bill_code,vtiger_soshipads.ship_code,vtiger_sobillads.bill_country,vtiger_soshipads.ship_country,vtiger_salesorder.terms_conditions,vtiger_salesorder.tandc,vtiger_crmentity.description,vtiger_salesorder.pl_gross_total,vtiger_salesorder.pl_dto_line,vtiger_salesorder.pl_dto_total,vtiger_salesorder.pl_dto_global,vtiger_salesorder.pl_net_total,vtiger_salesorder.sum_nettotal,vtiger_salesorder.sum_taxtotal,vtiger_salesorder.sum_tax1,vtiger_salesorder.sum_taxtotalretention,vtiger_salesorder.sum_tax2,vtiger_salesorder.pl_sh_total,vtiger_salesorder.sum_tax3,vtiger_salesorder.pl_sh_tax,vtiger_salesorder.pl_grand_total,vtiger_salesorder.pl_adjustment,vtiger_salesorder.salesorderid FROM vtiger_salesorder LEFT JOIN vtiger_crmentity ON vtiger_salesorder.salesorderid=vtiger_crmentity.crmid LEFT JOIN vtiger_invoice_recurring_info ON vtiger_salesorder.salesorderid=vtiger_invoice_recurring_info.salesorderid LEFT JOIN vtiger_sobillads ON vtiger_salesorder.salesorderid=vtiger_sobillads.sobilladdressid LEFT JOIN vtiger_soshipads ON vtiger_salesorder.salesorderid=vtiger_soshipads.soshipaddressid   WHERE  vtiger_crmentity.deleted=0 LIMIT 100;", $actual);
		$actual = self::$vtModuleOperation->wsVTQL2SQL('select * from Quotes;', $meta, $queryRelatedModules);
		$this->assertEquals("SELECT vtiger_quotes.subject,vtiger_quotes.potentialid,vtiger_quotes.quote_no,vtiger_quotes.quotestage,vtiger_quotes.validtill,vtiger_quotes.contactid,vtiger_quotes.carrier,vtiger_quotes.subtotal,vtiger_quotes.shipping,vtiger_quotes.inventorymanager,vtiger_quotes.total,vtiger_quotes.taxtype,vtiger_quotes.discount_percent,vtiger_quotes.discount_amount,vtiger_quotes.s_h_amount,vtiger_quotes.accountid,vtiger_crmentity.smownerid,vtiger_crmentity.createdtime,vtiger_crmentity.modifiedtime,vtiger_quotes.adjustment,vtiger_quotes.currency_id,vtiger_quotes.conversion_rate,vtiger_crmentity.modifiedby,vtiger_quotescf.cf_747,vtiger_crmentity.smcreatorid,vtiger_quotesbillads.bill_street,vtiger_quotesshipads.ship_street,vtiger_quotesbillads.bill_pobox,vtiger_quotesshipads.ship_pobox,vtiger_quotesbillads.bill_city,vtiger_quotesshipads.ship_city,vtiger_quotesbillads.bill_state,vtiger_quotesshipads.ship_state,vtiger_quotesbillads.bill_code,vtiger_quotesshipads.ship_code,vtiger_quotesbillads.bill_country,vtiger_quotesshipads.ship_country,vtiger_quotes.terms_conditions,vtiger_quotes.tandc,vtiger_crmentity.description,vtiger_quotes.pl_gross_total,vtiger_quotes.pl_dto_line,vtiger_quotes.pl_dto_total,vtiger_quotes.pl_dto_global,vtiger_quotes.pl_net_total,vtiger_quotes.sum_nettotal,vtiger_quotes.sum_taxtotal,vtiger_quotes.sum_tax1,vtiger_quotes.sum_taxtotalretention,vtiger_quotes.sum_tax2,vtiger_quotes.pl_sh_total,vtiger_quotes.sum_tax3,vtiger_quotes.pl_sh_tax,vtiger_quotes.pl_grand_total,vtiger_quotes.pl_adjustment,vtiger_quotes.quoteid FROM vtiger_quotes LEFT JOIN vtiger_crmentity ON vtiger_quotes.quoteid=vtiger_crmentity.crmid LEFT JOIN vtiger_quotescf ON vtiger_quotes.quoteid=vtiger_quotescf.quoteid LEFT JOIN vtiger_quotesbillads ON vtiger_quotes.quoteid=vtiger_quotesbillads.quotebilladdressid LEFT JOIN vtiger_quotesshipads ON vtiger_quotes.quoteid=vtiger_quotesshipads.quoteshipaddressid   WHERE  vtiger_crmentity.deleted=0 LIMIT 100;", $actual);
		$actual = self::$vtModuleOperation->wsVTQL2SQL('select * from PurchaseOrder;', $meta, $queryRelatedModules);
		$actual = $this->sortQueryColumns($actual);
		$this->assertEquals("SELECT vtiger_crmentity.createdtime,vtiger_crmentity.description,vtiger_crmentity.modifiedby,vtiger_crmentity.modifiedtime,vtiger_crmentity.smcreatorid,vtiger_crmentity.smownerid,vtiger_pobillads.bill_city,vtiger_pobillads.bill_code,vtiger_pobillads.bill_country,vtiger_pobillads.bill_pobox,vtiger_pobillads.bill_state,vtiger_pobillads.bill_street,vtiger_poshipads.ship_city,vtiger_poshipads.ship_code,vtiger_poshipads.ship_country,vtiger_poshipads.ship_pobox,vtiger_poshipads.ship_state,vtiger_poshipads.ship_street,vtiger_purchaseorder.adjustment,vtiger_purchaseorder.carrier,vtiger_purchaseorder.contactid,vtiger_purchaseorder.conversion_rate,vtiger_purchaseorder.currency_id,vtiger_purchaseorder.discount_amount,vtiger_purchaseorder.discount_percent,vtiger_purchaseorder.duedate,vtiger_purchaseorder.exciseduty,vtiger_purchaseorder.pl_adjustment,vtiger_purchaseorder.pl_dto_global,vtiger_purchaseorder.pl_dto_line,vtiger_purchaseorder.pl_dto_total,vtiger_purchaseorder.pl_grand_total,vtiger_purchaseorder.pl_gross_total,vtiger_purchaseorder.pl_net_total,vtiger_purchaseorder.pl_sh_tax,vtiger_purchaseorder.pl_sh_total,vtiger_purchaseorder.postatus,vtiger_purchaseorder.purchaseorder_no,vtiger_purchaseorder.purchaseorderid,vtiger_purchaseorder.requisition_no,vtiger_purchaseorder.s_h_amount,vtiger_purchaseorder.salescommission,vtiger_purchaseorder.subject,vtiger_purchaseorder.subtotal,vtiger_purchaseorder.sum_nettotal,vtiger_purchaseorder.sum_tax1,vtiger_purchaseorder.sum_tax2,vtiger_purchaseorder.sum_tax3,vtiger_purchaseorder.sum_taxtotal,vtiger_purchaseorder.sum_taxtotalretention,vtiger_purchaseorder.tandc,vtiger_purchaseorder.taxtype,vtiger_purchaseorder.terms_conditions,vtiger_purchaseorder.total,vtiger_purchaseorder.tracking_no,vtiger_purchaseorder.vendorid FROM vtiger_purchaseorder LEFT JOIN vtiger_crmentity ON vtiger_purchaseorder.purchaseorderid=vtiger_crmentity.crmid LEFT JOIN vtiger_pobillads ON vtiger_purchaseorder.purchaseorderid=vtiger_pobillads.pobilladdressid LEFT JOIN vtiger_poshipads ON vtiger_purchaseorder.purchaseorderid=vtiger_poshipads.poshipaddressid   WHERE  vtiger_crmentity.deleted=0 LIMIT 100;", $actual);
		$actual = self::$vtModuleOperation->wsVTQL2SQL('select * from Invoice;', $meta, $queryRelatedModules);
		$actual = $this->sortQueryColumns($actual);
		$this->assertEquals("SELECT vtiger_crmentity.createdtime,vtiger_crmentity.description,vtiger_crmentity.modifiedby,vtiger_crmentity.modifiedtime,vtiger_crmentity.smcreatorid,vtiger_crmentity.smownerid,vtiger_invoice.accountid,vtiger_invoice.adjustment,vtiger_invoice.contactid,vtiger_invoice.conversion_rate,vtiger_invoice.currency_id,vtiger_invoice.customerno,vtiger_invoice.discount_amount,vtiger_invoice.discount_percent,vtiger_invoice.duedate,vtiger_invoice.exciseduty,vtiger_invoice.invoice_no,vtiger_invoice.invoicedate,vtiger_invoice.invoiceid,vtiger_invoice.invoicestatus,vtiger_invoice.pl_adjustment,vtiger_invoice.pl_dto_global,vtiger_invoice.pl_dto_line,vtiger_invoice.pl_dto_total,vtiger_invoice.pl_grand_total,vtiger_invoice.pl_gross_total,vtiger_invoice.pl_net_total,vtiger_invoice.pl_sh_tax,vtiger_invoice.pl_sh_total,vtiger_invoice.purchaseorder,vtiger_invoice.s_h_amount,vtiger_invoice.salescommission,vtiger_invoice.salesorderid,vtiger_invoice.subject,vtiger_invoice.subtotal,vtiger_invoice.sum_nettotal,vtiger_invoice.sum_tax1,vtiger_invoice.sum_tax2,vtiger_invoice.sum_tax3,vtiger_invoice.sum_taxtotal,vtiger_invoice.sum_taxtotalretention,vtiger_invoice.tandc,vtiger_invoice.taxtype,vtiger_invoice.terms_conditions,vtiger_invoice.total,vtiger_invoicebillads.bill_city,vtiger_invoicebillads.bill_code,vtiger_invoicebillads.bill_country,vtiger_invoicebillads.bill_pobox,vtiger_invoicebillads.bill_state,vtiger_invoicebillads.bill_street,vtiger_invoiceshipads.ship_city,vtiger_invoiceshipads.ship_code,vtiger_invoiceshipads.ship_country,vtiger_invoiceshipads.ship_pobox,vtiger_invoiceshipads.ship_state,vtiger_invoiceshipads.ship_street FROM vtiger_invoice LEFT JOIN vtiger_crmentity ON vtiger_invoice.invoiceid=vtiger_crmentity.crmid LEFT JOIN vtiger_invoicebillads ON vtiger_invoice.invoiceid=vtiger_invoicebillads.invoicebilladdressid LEFT JOIN vtiger_invoiceshipads ON vtiger_invoice.invoiceid=vtiger_invoiceshipads.invoiceshipaddressid   WHERE  vtiger_crmentity.deleted=0 LIMIT 100;", $actual);
	}

	public function testParenthesis() {
		global $current_user;
		$actual = self::$vtModuleOperation->wsVTQL2SQL("select id from Leads where (email='j@t.tld' or secondaryemail='j@t.tld') and createdtime>='2016-01-01';", $meta, $queryRelatedModules);
		$this->assertEquals("select vtiger_leaddetails.leadid, vtiger_leaddetails.leadid  FROM vtiger_leaddetails  INNER JOIN vtiger_crmentity ON vtiger_leaddetails.leadid = vtiger_crmentity.crmid   WHERE vtiger_crmentity.deleted=0 and vtiger_leaddetails.converted=0 AND   (  (( vtiger_leaddetails.email = 'j@t.tld')  OR ( vtiger_leaddetails.secondaryemail = 'j@t.tld') ) AND ( vtiger_crmentity.createdtime >= '2016-01-01') ) AND vtiger_leaddetails.leadid > 0 ", $actual);
		$actual = self::$vtModuleOperation->wsVTQL2SQL("select id from Leads where createdtime>='2016-01-01' and (email='j@t.tld' or secondaryemail='j@t.tld');", $meta, $queryRelatedModules);
		$this->assertEquals("select vtiger_leaddetails.leadid, vtiger_leaddetails.leadid  FROM vtiger_leaddetails  INNER JOIN vtiger_crmentity ON vtiger_leaddetails.leadid = vtiger_crmentity.crmid   WHERE vtiger_crmentity.deleted=0 and vtiger_leaddetails.converted=0 AND   (( vtiger_crmentity.createdtime >= '2016-01-01')  AND (( vtiger_leaddetails.email = 'j@t.tld')  OR ( vtiger_leaddetails.secondaryemail = 'j@t.tld') )) AND vtiger_leaddetails.leadid > 0 ", $actual);
		$obj = new QueryGenerator('Project', $current_user);
		$denorm = $obj->denormalized;
		if ($denorm) {
			$actual = self::$vtModuleOperation->wsVTQL2SQL("select projectname,modifiedtime from project where (projectname like '%o%'  and modifiedtime>'2016-06-30 19:11:59');", $meta, $queryRelatedModules);
			$this->assertEquals("select vtiger_project.projectname, vtiger_project.mymodifiedtime, vtiger_project.projectid  FROM vtiger_project   WHERE vtiger_project.mydeleted=0 AND   (  (( vtiger_project.projectname LIKE '%o%')  AND ( vtiger_project.mymodifiedtime > '2016-06-30 19:11:59') )) AND vtiger_project.projectid > 0 ", $actual);
			$actual = self::$vtModuleOperation->wsVTQL2SQL("select projectname,modifiedtime from project where projectname like '%o%'  and mymodifiedtime>'2016-06-30 19:11:59';", $meta, $queryRelatedModules);
			$this->assertEquals("SELECT vtiger_project.projectname,vtiger_crmentity.modifiedtime,vtiger_project.projectid FROM vtiger_project LEFT JOIN vtiger_crmentity ON vtiger_project.projectid=vtiger_crmentity.crmid   WHERE (vtiger_project.projectname LIKE '%o%' and vtiger_crmentity.modifiedtime > '2016-06-30 19:11:59') AND  vtiger_crmentity.deleted=0 LIMIT 100;", $actual);
		} else {
			$actual = self::$vtModuleOperation->wsVTQL2SQL("select projectname,modifiedtime from project where (projectname like '%o%'  and modifiedtime>'2016-06-30 19:11:59');", $meta, $queryRelatedModules);
			$this->assertEquals("select vtiger_project.projectname, vtiger_crmentity.modifiedtime, vtiger_project.projectid  FROM vtiger_project  INNER JOIN vtiger_crmentity ON vtiger_project.projectid = vtiger_crmentity.crmid   WHERE vtiger_crmentity.deleted=0 AND   (  (( vtiger_project.projectname LIKE '%o%')  AND ( vtiger_crmentity.modifiedtime > '2016-06-30 19:11:59') )) AND vtiger_project.projectid > 0 ", $actual);
			$actual = self::$vtModuleOperation->wsVTQL2SQL("select projectname,modifiedtime from project where projectname like '%o%'  and modifiedtime>'2016-06-30 19:11:59';", $meta, $queryRelatedModules);
			$this->assertEquals("SELECT vtiger_project.projectname,vtiger_crmentity.modifiedtime,vtiger_project.projectid FROM vtiger_project LEFT JOIN vtiger_crmentity ON vtiger_project.projectid=vtiger_crmentity.crmid   WHERE (vtiger_project.projectname LIKE '%o%' and vtiger_crmentity.modifiedtime > '2016-06-30 19:11:59') AND  vtiger_crmentity.deleted=0 LIMIT 100;", $actual);
		}
	}

	public function testControlCharacters() {
		global $current_user;
		$actual = self::$vtModuleOperation->wsVTQL2SQL("select	id
 from Leads
where (email='j@t.tld' or secondaryemail='j@t.tld') and createdtime>='2016-01-01';", $meta, $queryRelatedModules);
		$this->assertEquals("select vtiger_leaddetails.leadid, vtiger_leaddetails.leadid  FROM vtiger_leaddetails  INNER JOIN vtiger_crmentity ON vtiger_leaddetails.leadid = vtiger_crmentity.crmid   WHERE vtiger_crmentity.deleted=0 and vtiger_leaddetails.converted=0 AND   (  (( vtiger_leaddetails.email = 'j@t.tld')  OR ( vtiger_leaddetails.secondaryemail = 'j@t.tld') ) AND ( vtiger_crmentity.createdtime >= '2016-01-01') ) AND vtiger_leaddetails.leadid > 0 ", $actual);
		$obj = new QueryGenerator('Project', $current_user);
		$denorm = $obj->denormalized;
		if ($denorm) {
			$actual = self::$vtModuleOperation->wsVTQL2SQL("select	projectname,modifiedtime
	 from project
	where (projectname like '%o%'  and modifiedtime>'2016-06-30 19:11:59');", $meta, $queryRelatedModules);
			$this->assertEquals("select vtiger_project.projectname, vtiger_project.mymodifiedtime, vtiger_project.projectid  FROM vtiger_project   WHERE vtiger_project.mydeleted=0 AND   (  (( vtiger_project.projectname LIKE '%o%')  AND ( vtiger_project.mymodifiedtime > '2016-06-30 19:11:59') )) AND vtiger_project.projectid > 0 ", $actual);
			$actual = self::$vtModuleOperation->wsVTQL2SQL("select	projectname,modifiedtime
	 from project
	where projectname like '%o%'  and modifiedtime>'2016-06-30 19:11:59';", $meta, $queryRelatedModules);
			$this->assertEquals("SELECT vtiger_project.projectname,vtiger_crmentity.modifiedtime,vtiger_project.projectid FROM vtiger_project LEFT JOIN vtiger_crmentity ON vtiger_project.projectid=vtiger_crmentity.crmid   WHERE (vtiger_project.projectname LIKE '%o%' and vtiger_crmentity.modifiedtime > '2016-06-30 19:11:59') AND  vtiger_crmentity.deleted=0 LIMIT 100;", $actual);
		} else {
			$actual = self::$vtModuleOperation->wsVTQL2SQL("select	projectname,modifiedtime
	 from project
	where (projectname like '%o%'  and modifiedtime>'2016-06-30 19:11:59');", $meta, $queryRelatedModules);
			$this->assertEquals("select vtiger_project.projectname, vtiger_crmentity.modifiedtime, vtiger_project.projectid  FROM vtiger_project  INNER JOIN vtiger_crmentity ON vtiger_project.projectid = vtiger_crmentity.crmid   WHERE vtiger_crmentity.deleted=0 AND   (  (( vtiger_project.projectname LIKE '%o%')  AND ( vtiger_crmentity.modifiedtime > '2016-06-30 19:11:59') )) AND vtiger_project.projectid > 0 ", $actual);
			$actual = self::$vtModuleOperation->wsVTQL2SQL("select	projectname,modifiedtime
	 from project
	where projectname like '%o%'  and modifiedtime>'2016-06-30 19:11:59';", $meta, $queryRelatedModules);
			$this->assertEquals("SELECT vtiger_project.projectname,vtiger_crmentity.modifiedtime,vtiger_project.projectid FROM vtiger_project LEFT JOIN vtiger_crmentity ON vtiger_project.projectid=vtiger_crmentity.crmid   WHERE (vtiger_project.projectname LIKE '%o%' and vtiger_crmentity.modifiedtime > '2016-06-30 19:11:59') AND  vtiger_crmentity.deleted=0 LIMIT 100;", $actual);
		}
	}

	public function testQuotes() {
		$actual = self::$vtModuleOperation->wsVTQL2SQL("select id,firstname,lastname from Contacts where firstname LIKE '%''ele%';", $meta, $queryRelatedModules);
		$this->assertEquals("SELECT vtiger_contactdetails.contactid,vtiger_contactdetails.firstname,vtiger_contactdetails.lastname FROM vtiger_contactdetails LEFT JOIN vtiger_crmentity ON vtiger_contactdetails.contactid=vtiger_crmentity.crmid   WHERE (vtiger_contactdetails.firstname LIKE '%''ele%') AND  vtiger_crmentity.deleted=0 LIMIT 100;", $actual);

		$actual = self::$vtModuleOperation->wsVTQL2SQL("select id,firstname,lastname from Contacts where firstname LIKE '%ele%' LIMIT 0, 250;", $meta, $queryRelatedModules);
		$this->assertEquals("SELECT vtiger_contactdetails.contactid,vtiger_contactdetails.firstname,vtiger_contactdetails.lastname FROM vtiger_contactdetails LEFT JOIN vtiger_crmentity ON vtiger_contactdetails.contactid=vtiger_crmentity.crmid   WHERE (vtiger_contactdetails.firstname LIKE '%ele%') AND  vtiger_crmentity.deleted=0 LIMIT 0,250;", $actual);

		$actual = self::$vtModuleOperation->wsVTQL2SQL("select id,firstname,lastname from Contacts where (firstname LIKE '%''ele%');", $meta, $queryRelatedModules);
		$this->assertEquals("select vtiger_contactdetails.contactid, vtiger_contactdetails.firstname, vtiger_contactdetails.lastname, vtiger_contactdetails.contactid  FROM vtiger_contactdetails  INNER JOIN vtiger_crmentity ON vtiger_contactdetails.contactid = vtiger_crmentity.crmid   WHERE vtiger_crmentity.deleted=0 AND   (  (( vtiger_contactdetails.firstname LIKE '%\'ele%') )) AND vtiger_contactdetails.contactid > 0 ", $actual);

		$actual = self::$vtModuleOperation->wsVTQL2SQL("select id,firstname,lastname from Contacts where Contacts.firstname LIKE '%''ele%';", $meta, $queryRelatedModules);
		$this->assertEquals("select vtiger_contactdetails.contactid, vtiger_contactdetails.firstname, vtiger_contactdetails.lastname, vtiger_contactdetails.contactid  FROM vtiger_contactdetails  INNER JOIN vtiger_crmentity ON vtiger_contactdetails.contactid = vtiger_crmentity.crmid LEFT JOIN vtiger_contactdetails AS vtiger_contactdetailscontact_id ON vtiger_contactdetailscontact_id.contactid=vtiger_contactdetails.reportsto   WHERE vtiger_crmentity.deleted=0 AND   ((vtiger_contactdetailscontact_id.firstname LIKE '%\'ele%') ) AND vtiger_contactdetails.contactid > 0 ", $actual);
	}

	public function testConditions() {
		global $current_user;
		$actual = self::$vtModuleOperation->wsVTQL2SQL("select accountname,website from Accounts where website like '%vt%';", $meta, $queryRelatedModules);
		$this->assertEquals("SELECT vtiger_account.accountname,vtiger_account.website,vtiger_account.accountid FROM vtiger_account LEFT JOIN vtiger_crmentity ON vtiger_account.accountid=vtiger_crmentity.crmid   WHERE (vtiger_account.website LIKE '%vt%') AND  vtiger_crmentity.deleted=0 LIMIT 100;", $actual);
		$actual = self::$vtModuleOperation->wsVTQL2SQL("select firstname, lastname from Contacts where firstname like '%o%' order by firstname desc limit 0,22;", $meta, $queryRelatedModules);
		$this->assertEquals("SELECT vtiger_contactdetails.firstname,vtiger_contactdetails.lastname,vtiger_contactdetails.contactid FROM vtiger_contactdetails LEFT JOIN vtiger_crmentity ON vtiger_contactdetails.contactid=vtiger_crmentity.crmid   WHERE (vtiger_contactdetails.firstname LIKE '%o%') AND  vtiger_crmentity.deleted=0 ORDER BY vtiger_contactdetails.firstname DESC LIMIT 0,22;", $actual);
		$actual = self::$vtModuleOperation->wsVTQL2SQL("select accountname,website from Accounts where website in ('www.edfggrouplimited.com','www.gooduivtiger.com');", $meta, $queryRelatedModules);
		$this->assertEquals("SELECT vtiger_account.accountname,vtiger_account.website,vtiger_account.accountid FROM vtiger_account LEFT JOIN vtiger_crmentity ON vtiger_account.accountid=vtiger_crmentity.crmid   WHERE (vtiger_account.website IN ('www.edfggrouplimited.com','www.gooduivtiger.com')) AND  vtiger_crmentity.deleted=0 LIMIT 100;", $actual);
		$actual = self::$vtModuleOperation->wsVTQL2SQL("select accountname,website,Accounts.accountname,Accounts.website from Accounts where website in ('www.edfggrouplimited.com','www.gooduivtiger.com');", $meta, $queryRelatedModules);
		$this->assertEquals("select vtiger_account.accountname, vtiger_account.website, vtiger_accountaccount_id.accountname as accountsaccountname, vtiger_accountaccount_id.website as accountswebsite, vtiger_account.accountid  FROM vtiger_account  INNER JOIN vtiger_crmentity ON vtiger_account.accountid = vtiger_crmentity.crmid LEFT JOIN vtiger_account AS vtiger_accountaccount_id ON vtiger_accountaccount_id.accountid=vtiger_account.parentid   WHERE vtiger_crmentity.deleted=0 AND   (( vtiger_account.website IN ('www.edfggrouplimited.com','www.gooduivtiger.com')) ) AND vtiger_account.accountid > 0 ", $actual);
		$actual = self::$vtModuleOperation->wsVTQL2SQL("select accountname,website,Accounts.accountname,Accounts.website from Accounts where Accounts.website in ('www.edfggrouplimited.com','www.gooduivtiger.com');", $meta, $queryRelatedModules);
		$this->assertEquals("select vtiger_account.accountname, vtiger_account.website, vtiger_accountaccount_id.accountname as accountsaccountname, vtiger_accountaccount_id.website as accountswebsite, vtiger_account.accountid  FROM vtiger_account  INNER JOIN vtiger_crmentity ON vtiger_account.accountid = vtiger_crmentity.crmid LEFT JOIN vtiger_account AS vtiger_accountaccount_id ON vtiger_accountaccount_id.accountid=vtiger_account.parentid   WHERE vtiger_crmentity.deleted=0 AND   ((vtiger_accountaccount_id.website IN ('www.edfggrouplimited.com','www.gooduivtiger.com')) ) AND vtiger_account.accountid > 0 ", $actual);

		$actual = self::$vtModuleOperation->wsVTQL2SQL("select accountname,website from Accounts where website not in ('www.edfggrouplimited.com','www.gooduivtiger.com');", $meta, $queryRelatedModules);
		$this->assertEquals("select vtiger_account.accountname, vtiger_account.website, vtiger_account.accountid  FROM vtiger_account  INNER JOIN vtiger_crmentity ON vtiger_account.accountid = vtiger_crmentity.crmid   WHERE vtiger_crmentity.deleted=0 AND   (( vtiger_account.website  NOT IN ('www.edfggrouplimited.com','www.gooduivtiger.com')) ) AND vtiger_account.accountid > 0 ", $actual);
		$actual = self::$vtModuleOperation->wsVTQL2SQL("select accountname,website,Accounts.accountname,Accounts.website from Accounts where website not in ('www.edfggrouplimited.com','www.gooduivtiger.com');", $meta, $queryRelatedModules);
		$this->assertEquals("select vtiger_account.accountname, vtiger_account.website, vtiger_accountaccount_id.accountname as accountsaccountname, vtiger_accountaccount_id.website as accountswebsite, vtiger_account.accountid  FROM vtiger_account  INNER JOIN vtiger_crmentity ON vtiger_account.accountid = vtiger_crmentity.crmid LEFT JOIN vtiger_account AS vtiger_accountaccount_id ON vtiger_accountaccount_id.accountid=vtiger_account.parentid   WHERE vtiger_crmentity.deleted=0 AND   (( vtiger_account.website  NOT IN ('www.edfggrouplimited.com','www.gooduivtiger.com')) ) AND vtiger_account.accountid > 0 ", $actual);
		$actual = self::$vtModuleOperation->wsVTQL2SQL("select accountname,website,Accounts.accountname,Accounts.website from Accounts where Accounts.website not in ('www.edfggrouplimited.com','www.gooduivtiger.com');", $meta, $queryRelatedModules);
		$this->assertEquals("select vtiger_account.accountname, vtiger_account.website, vtiger_accountaccount_id.accountname as accountsaccountname, vtiger_accountaccount_id.website as accountswebsite, vtiger_account.accountid  FROM vtiger_account  INNER JOIN vtiger_crmentity ON vtiger_account.accountid = vtiger_crmentity.crmid LEFT JOIN vtiger_account AS vtiger_accountaccount_id ON vtiger_accountaccount_id.accountid=vtiger_account.parentid   WHERE vtiger_crmentity.deleted=0 AND   ((vtiger_accountaccount_id.website  NOT IN ('www.edfggrouplimited.com','www.gooduivtiger.com')) ) AND vtiger_account.accountid > 0 ", $actual);

		$actual = self::$vtModuleOperation->wsVTQL2SQL('select accountname,website,Accounts.accountname,Accounts.website from Accounts;', $meta, $queryRelatedModules);
		$this->assertEquals("select vtiger_account.accountname, vtiger_account.website, vtiger_accountaccount_id.accountname as accountsaccountname, vtiger_accountaccount_id.website as accountswebsite, vtiger_account.accountid  FROM vtiger_account  INNER JOIN vtiger_crmentity ON vtiger_account.accountid = vtiger_crmentity.crmid LEFT JOIN vtiger_account AS vtiger_accountaccount_id ON vtiger_accountaccount_id.accountid=vtiger_account.parentid   WHERE vtiger_crmentity.deleted=0 AND vtiger_account.accountid > 0 ", $actual);
		$actual = self::$vtModuleOperation->wsVTQL2SQL("select accountname,website,Accounts.accountname,Accounts.website from Accounts where website like '%vt%'", $meta, $queryRelatedModules);
		$this->assertEquals("select vtiger_account.accountname, vtiger_account.website, vtiger_accountaccount_id.accountname as accountsaccountname, vtiger_accountaccount_id.website as accountswebsite, vtiger_account.accountid  FROM vtiger_account  INNER JOIN vtiger_crmentity ON vtiger_account.accountid = vtiger_crmentity.crmid LEFT JOIN vtiger_account AS vtiger_accountaccount_id ON vtiger_accountaccount_id.accountid=vtiger_account.parentid   WHERE vtiger_crmentity.deleted=0 AND   (( vtiger_account.website LIKE '%vt%') ) AND vtiger_account.accountid > 0 ", $actual);
		$actual = self::$vtModuleOperation->wsVTQL2SQL("select accountname,website,Accounts.accountname,Accounts.website from Accounts where Accounts.website like '%vt%'", $meta, $queryRelatedModules);
		$this->assertEquals("select vtiger_account.accountname, vtiger_account.website, vtiger_accountaccount_id.accountname as accountsaccountname, vtiger_accountaccount_id.website as accountswebsite, vtiger_account.accountid  FROM vtiger_account  INNER JOIN vtiger_crmentity ON vtiger_account.accountid = vtiger_crmentity.crmid LEFT JOIN vtiger_account AS vtiger_accountaccount_id ON vtiger_accountaccount_id.accountid=vtiger_account.parentid   WHERE vtiger_crmentity.deleted=0 AND   ((vtiger_accountaccount_id.website LIKE '%vt%') ) AND vtiger_account.accountid > 0 ", $actual);
		$actual = self::$vtModuleOperation->wsVTQL2SQL("select id, accountname, Accounts.accountname from Accounts where accountname != 'PK UNA' limit 10", $meta, $queryRelatedModules);
		$this->assertEquals("select vtiger_account.accountid, vtiger_account.accountname, vtiger_accountaccount_id.accountname as accountsaccountname, vtiger_account.accountid  FROM vtiger_account  INNER JOIN vtiger_crmentity ON vtiger_account.accountid = vtiger_crmentity.crmid LEFT JOIN vtiger_account AS vtiger_accountaccount_id ON vtiger_accountaccount_id.accountid=vtiger_account.parentid   WHERE vtiger_crmentity.deleted=0 AND   (( vtiger_account.accountname <> 'PK UNA') ) AND vtiger_account.accountid > 0  limit 10 ", $actual);

		$actual = self::$vtModuleOperation->wsVTQL2SQL("select firstname, lastname,Accounts.accountname,Accounts.website from Contacts where firstname like '%o%' order by firstname desc limit 0,22;", $meta, $queryRelatedModules);
		$this->assertEquals("select vtiger_contactdetails.firstname, vtiger_contactdetails.lastname, vtiger_accountaccount_id.accountname as accountsaccountname, vtiger_accountaccount_id.website as accountswebsite, vtiger_contactdetails.contactid  FROM vtiger_contactdetails  INNER JOIN vtiger_crmentity ON vtiger_contactdetails.contactid = vtiger_crmentity.crmid LEFT JOIN vtiger_account AS vtiger_accountaccount_id ON vtiger_accountaccount_id.accountid=vtiger_contactdetails.accountid   WHERE vtiger_crmentity.deleted=0 AND   (( vtiger_contactdetails.firstname LIKE '%o%') ) AND vtiger_contactdetails.contactid > 0  order by firstname desc   limit 0,22 ", $actual);
		$actual = self::$vtModuleOperation->wsVTQL2SQL("select firstname, lastname,Accounts.accountname,Accounts.website from Contacts where firstname like '%o%' order by firstname asc", $meta, $queryRelatedModules);
		$this->assertEquals("select vtiger_contactdetails.firstname, vtiger_contactdetails.lastname, vtiger_accountaccount_id.accountname as accountsaccountname, vtiger_accountaccount_id.website as accountswebsite, vtiger_contactdetails.contactid  FROM vtiger_contactdetails  INNER JOIN vtiger_crmentity ON vtiger_contactdetails.contactid = vtiger_crmentity.crmid LEFT JOIN vtiger_account AS vtiger_accountaccount_id ON vtiger_accountaccount_id.accountid=vtiger_contactdetails.accountid   WHERE vtiger_crmentity.deleted=0 AND   (( vtiger_contactdetails.firstname LIKE '%o%') ) AND vtiger_contactdetails.contactid > 0  order by firstname asc  ", $actual);
		$actual = self::$vtModuleOperation->wsVTQL2SQL("select firstname, lastname,Accounts.accountname,Accounts.website from Contacts where firstname like '%o%' limit 0,22;", $meta, $queryRelatedModules);
		$this->assertEquals("select vtiger_contactdetails.firstname, vtiger_contactdetails.lastname, vtiger_accountaccount_id.accountname as accountsaccountname, vtiger_accountaccount_id.website as accountswebsite, vtiger_contactdetails.contactid  FROM vtiger_contactdetails  INNER JOIN vtiger_crmentity ON vtiger_contactdetails.contactid = vtiger_crmentity.crmid LEFT JOIN vtiger_account AS vtiger_accountaccount_id ON vtiger_accountaccount_id.accountid=vtiger_contactdetails.accountid   WHERE vtiger_crmentity.deleted=0 AND   (( vtiger_contactdetails.firstname LIKE '%o%') ) AND vtiger_contactdetails.contactid > 0  limit 0,22 ", $actual);
		$actual = self::$vtModuleOperation->wsVTQL2SQL("select firstname, lastname,Accounts.accountname,Accounts.website from Contacts where firstname like '%o%'", $meta, $queryRelatedModules);
		$this->assertEquals("select vtiger_contactdetails.firstname, vtiger_contactdetails.lastname, vtiger_accountaccount_id.accountname as accountsaccountname, vtiger_accountaccount_id.website as accountswebsite, vtiger_contactdetails.contactid  FROM vtiger_contactdetails  INNER JOIN vtiger_crmentity ON vtiger_contactdetails.contactid = vtiger_crmentity.crmid LEFT JOIN vtiger_account AS vtiger_accountaccount_id ON vtiger_accountaccount_id.accountid=vtiger_contactdetails.accountid   WHERE vtiger_crmentity.deleted=0 AND   (( vtiger_contactdetails.firstname LIKE '%o%') ) AND vtiger_contactdetails.contactid > 0 ", $actual);

		$actual = self::$vtModuleOperation->wsVTQL2SQL("select firstname, lastname,Accounts.accountname from Contacts where firstname like '%o%' or firstname like '%e%' or firstname like '%s%'", $meta, $queryRelatedModules);
		$this->assertEquals("select vtiger_contactdetails.firstname, vtiger_contactdetails.lastname, vtiger_accountaccount_id.accountname as accountsaccountname, vtiger_contactdetails.contactid  FROM vtiger_contactdetails  INNER JOIN vtiger_crmentity ON vtiger_contactdetails.contactid = vtiger_crmentity.crmid LEFT JOIN vtiger_account AS vtiger_accountaccount_id ON vtiger_accountaccount_id.accountid=vtiger_contactdetails.accountid   WHERE vtiger_crmentity.deleted=0 AND   (( vtiger_contactdetails.firstname LIKE '%o%')  OR ( vtiger_contactdetails.firstname LIKE '%e%')  OR ( vtiger_contactdetails.firstname LIKE '%s%') ) AND vtiger_contactdetails.contactid > 0 ", $actual);
		$actual = self::$vtModuleOperation->wsVTQL2SQL("select firstname, lastname,Accounts.accountname from Contacts where firstname like '%o%' or (firstname like '%e%' and firstname like '%s%')", $meta, $queryRelatedModules);
		$this->assertEquals("select vtiger_contactdetails.firstname, vtiger_contactdetails.lastname, vtiger_accountaccount_id.accountname as accountsaccountname, vtiger_contactdetails.contactid  FROM vtiger_contactdetails  INNER JOIN vtiger_crmentity ON vtiger_contactdetails.contactid = vtiger_crmentity.crmid LEFT JOIN vtiger_account AS vtiger_accountaccount_id ON vtiger_accountaccount_id.accountid=vtiger_contactdetails.accountid   WHERE vtiger_crmentity.deleted=0 AND   (( vtiger_contactdetails.firstname LIKE '%o%')  OR (( vtiger_contactdetails.firstname LIKE '%e%')  AND ( vtiger_contactdetails.firstname LIKE '%s%') )) AND vtiger_contactdetails.contactid > 0 ", $actual);
		$actual = self::$vtModuleOperation->wsVTQL2SQL("select firstname from Contacts where Accounts.accountname != 'PK UNA' limit 10;", $meta, $queryRelatedModules);
		$this->assertEquals("select vtiger_contactdetails.firstname, vtiger_contactdetails.contactid  FROM vtiger_contactdetails  INNER JOIN vtiger_crmentity ON vtiger_contactdetails.contactid = vtiger_crmentity.crmid LEFT JOIN vtiger_account AS vtiger_accountaccount_id ON vtiger_accountaccount_id.accountid=vtiger_contactdetails.accountid   WHERE vtiger_crmentity.deleted=0 AND   ((vtiger_accountaccount_id.accountname <> 'PK UNA') ) AND vtiger_contactdetails.contactid > 0  limit 10 ", $actual);
		$actual = self::$vtModuleOperation->wsVTQL2SQL("select potentialname from Potentials where Accounts.accountname != 'PK UNA' limit 10;", $meta, $queryRelatedModules);
		$this->assertEquals("select vtiger_potential.potentialname, vtiger_potential.potentialid  FROM vtiger_potential  INNER JOIN vtiger_crmentity ON vtiger_potential.potentialid = vtiger_crmentity.crmid LEFT JOIN vtiger_account AS vtiger_accountrelated_to ON vtiger_accountrelated_to.accountid=vtiger_potential.related_to   WHERE vtiger_crmentity.deleted=0 AND   ((vtiger_accountrelated_to.accountname <> 'PK UNA') ) AND vtiger_potential.potentialid > 0  limit 10 ", $actual);
		$obj = new QueryGenerator('CobroPago', $current_user);
		$denorm = $obj->denormalized;
		$actual = self::$vtModuleOperation->wsVTQL2SQL("select cyp_no from CobroPago where Assets.assetname != 'PK UNA' limit 10;", $meta, $queryRelatedModules);
		if ($denorm) {
			$this->assertEquals("select vtiger_cobropago.cyp_no, vtiger_cobropago.cobropagoid  FROM vtiger_cobropago  LEFT JOIN vtiger_assets AS vtiger_assetsrelated_id ON vtiger_assetsrelated_id.assetsid=vtiger_cobropago.related_id  WHERE vtiger_cobropago.mydeleted=0 AND   ((vtiger_assetsrelated_id.assetname <> 'PK UNA') ) AND vtiger_cobropago.cobropagoid > 0  limit 10 ", $actual);
		} else {
			$this->assertEquals("select vtiger_cobropago.cyp_no, vtiger_cobropago.cobropagoid  FROM vtiger_cobropago  INNER JOIN vtiger_crmentity ON vtiger_cobropago.cobropagoid = vtiger_crmentity.crmid LEFT JOIN vtiger_assets AS vtiger_assetsrelated_id ON vtiger_assetsrelated_id.assetsid=vtiger_cobropago.related_id   WHERE vtiger_crmentity.deleted=0 AND   ((vtiger_assetsrelated_id.assetname <> 'PK UNA') ) AND vtiger_cobropago.cobropagoid > 0  limit 10 ", $actual);
		}
		$actual = self::$vtModuleOperation->wsVTQL2SQL("select firstname from Contacts where id in ('12x1084','12x1085');", $meta, $queryRelatedModules);
		$this->assertEquals("SELECT vtiger_contactdetails.firstname,vtiger_contactdetails.contactid FROM vtiger_contactdetails LEFT JOIN vtiger_crmentity ON vtiger_contactdetails.contactid=vtiger_crmentity.crmid   WHERE (vtiger_contactdetails.contactid IN (1084,1085)) AND  vtiger_crmentity.deleted=0 LIMIT 100;", $actual);
		$actual = self::$vtModuleOperation->wsVTQL2SQL("select firstname from Contacts where id not in ('12x1084','12x1085');", $meta, $queryRelatedModules);
		$this->assertEquals("select vtiger_contactdetails.firstname, vtiger_contactdetails.contactid  FROM vtiger_contactdetails  INNER JOIN vtiger_crmentity ON vtiger_contactdetails.contactid = vtiger_crmentity.crmid   WHERE vtiger_crmentity.deleted=0 AND   ((vtiger_contactdetails.contactid  NOT IN ('1084','1085')) ) AND vtiger_contactdetails.contactid > 0 ", $actual);

		$actual = self::$vtModuleOperation->wsVTQL2SQL("SELECT accountname FROM Accounts WHERE id IN ('11x75');", $meta, $queryRelatedModules);
		$this->assertEquals("SELECT vtiger_account.accountname,vtiger_account.accountid FROM vtiger_account LEFT JOIN vtiger_crmentity ON vtiger_account.accountid=vtiger_crmentity.crmid   WHERE (vtiger_account.accountid IN (75)) AND  vtiger_crmentity.deleted=0 LIMIT 100;", $actual);
		$actual = self::$vtModuleOperation->wsVTQL2SQL("SELECT accountname FROM Accounts WHERE id NOT IN ('11x75');", $meta, $queryRelatedModules);
		$this->assertEquals("select vtiger_account.accountname, vtiger_account.accountid  FROM vtiger_account  INNER JOIN vtiger_crmentity ON vtiger_account.accountid = vtiger_crmentity.crmid   WHERE vtiger_crmentity.deleted=0 AND   ((vtiger_account.accountid  NOT IN ('75')) ) AND vtiger_account.accountid > 0 ", $actual);

		$actual = self::$vtModuleOperation->wsVTQL2SQL("SELECT accountname FROM Accounts WHERE id IN ('11x74','11x75');", $meta, $queryRelatedModules);
		$this->assertEquals("SELECT vtiger_account.accountname,vtiger_account.accountid FROM vtiger_account LEFT JOIN vtiger_crmentity ON vtiger_account.accountid=vtiger_crmentity.crmid   WHERE (vtiger_account.accountid IN (74,75)) AND  vtiger_crmentity.deleted=0 LIMIT 100;", $actual);
		$actual = self::$vtModuleOperation->wsVTQL2SQL("SELECT accountname FROM Accounts WHERE id NOT IN ('11x74','11x75');", $meta, $queryRelatedModules);
		$this->assertEquals("select vtiger_account.accountname, vtiger_account.accountid  FROM vtiger_account  INNER JOIN vtiger_crmentity ON vtiger_account.accountid = vtiger_crmentity.crmid   WHERE vtiger_crmentity.deleted=0 AND   ((vtiger_account.accountid  NOT IN ('74','75')) ) AND vtiger_account.accountid > 0 ", $actual);

		$actual = self::$vtModuleOperation->wsVTQL2SQL("select Products.productname,assetname from Assets where assetname LIKE '%exy%';", $meta, $queryRelatedModules);
		$this->assertEquals("select vtiger_productsproduct.productname as productsproductname, vtiger_assets.assetname, vtiger_assets.assetsid  FROM vtiger_assets  INNER JOIN vtiger_crmentity ON vtiger_assets.assetsid = vtiger_crmentity.crmid LEFT JOIN vtiger_products AS vtiger_productsproduct ON vtiger_productsproduct.productid=vtiger_assets.product   WHERE vtiger_crmentity.deleted=0 AND   (( vtiger_assets.assetname LIKE '%exy%') ) AND vtiger_assets.assetsid > 0 ", $actual);
	}

	public function testQueryOnManyRelation() {
		// it is not supported so it eliminates all references to modules it cannot reach
		$actual = self::$vtModuleOperation->wsVTQL2SQL("select productname,Assets.assetname from Products where Assets.assetname LIKE '%exy%';", $meta, $queryRelatedModules);
		$this->assertEquals("select vtiger_products.productname, vtiger_products.productid  FROM vtiger_products  INNER JOIN vtiger_crmentity ON vtiger_products.productid = vtiger_crmentity.crmid   WHERE vtiger_crmentity.deleted=0 AND vtiger_products.productid > 0 ", $actual);
	}

	public function testQueryCountNull() {
		$actual = self::$vtModuleOperation->wsVTQL2SQL('select count(*) from accounts where accountname != null;', $meta, $queryRelatedModules);
		$this->assertEquals("SELECT  COUNT(*) FROM vtiger_account LEFT JOIN vtiger_crmentity ON vtiger_account.accountid=vtiger_crmentity.crmid   WHERE (vtiger_account.accountname != null) AND  vtiger_crmentity.deleted=0 LIMIT 100;", $actual);
		$actual = self::$vtModuleOperation->wsVTQL2SQL('select count(*) from accounts where accounts.accountname != null;', $meta, $queryRelatedModules);
		$this->assertEquals("select count(*)  FROM vtiger_account  INNER JOIN vtiger_crmentity ON vtiger_account.accountid = vtiger_crmentity.crmid LEFT JOIN vtiger_account AS vtiger_accountaccount_id ON vtiger_accountaccount_id.accountid=vtiger_account.parentid   WHERE vtiger_crmentity.deleted=0 AND   ((vtiger_accountaccount_id.accountname IS NOT NULL) ) AND vtiger_account.accountid > 0 ", $actual);
		$actual = self::$vtModuleOperation->wsVTQL2SQL('select count(*) from accounts where accountname is not null;', $meta, $queryRelatedModules);
		$this->assertEquals("select count(*)  FROM vtiger_account  INNER JOIN vtiger_crmentity ON vtiger_account.accountid = vtiger_crmentity.crmid   WHERE vtiger_crmentity.deleted=0 AND   (( vtiger_account.accountname IS NOT NULL AND vtiger_account.accountname != '') ) AND vtiger_account.accountid > 0 ", $actual);
		$actual = self::$vtModuleOperation->wsVTQL2SQL('select count(*) from accounts where accounts.accountname is not null;', $meta, $queryRelatedModules);
		$this->assertEquals("select count(*)  FROM vtiger_account  INNER JOIN vtiger_crmentity ON vtiger_account.accountid = vtiger_crmentity.crmid LEFT JOIN vtiger_account AS vtiger_accountaccount_id ON vtiger_accountaccount_id.accountid=vtiger_account.parentid   WHERE vtiger_crmentity.deleted=0 AND   ((vtiger_accountaccount_id.accountname IS NOT NULL AND vtiger_accountaccount_id.accountname != '') ) AND vtiger_account.accountid > 0 ", $actual);
		$actual = self::$vtModuleOperation->wsVTQL2SQL('select count(*) from accounts where accountname = null;', $meta, $queryRelatedModules);
		$this->assertEquals("SELECT  COUNT(*) FROM vtiger_account LEFT JOIN vtiger_crmentity ON vtiger_account.accountid=vtiger_crmentity.crmid   WHERE (vtiger_account.accountname = null) AND  vtiger_crmentity.deleted=0 LIMIT 100;", $actual);
		$actual = self::$vtModuleOperation->wsVTQL2SQL('select count(*) from accounts where accounts.accountname = null;', $meta, $queryRelatedModules);
		$this->assertEquals("select count(*)  FROM vtiger_account  INNER JOIN vtiger_crmentity ON vtiger_account.accountid = vtiger_crmentity.crmid LEFT JOIN vtiger_account AS vtiger_accountaccount_id ON vtiger_accountaccount_id.accountid=vtiger_account.parentid   WHERE vtiger_crmentity.deleted=0 AND   ((vtiger_accountaccount_id.accountname IS NULL) ) AND vtiger_account.accountid > 0 ", $actual);
		$actual = self::$vtModuleOperation->wsVTQL2SQL('select count(*) from accounts where accountname is null;', $meta, $queryRelatedModules);
		$this->assertEquals("select count(*)  FROM vtiger_account  INNER JOIN vtiger_crmentity ON vtiger_account.accountid = vtiger_crmentity.crmid   WHERE vtiger_crmentity.deleted=0 AND   (( vtiger_account.accountname IS NULL OR vtiger_account.accountname = '') ) AND vtiger_account.accountid > 0 ", $actual);
		$actual = self::$vtModuleOperation->wsVTQL2SQL('select count(*) from accounts where accounts.accountname is null;', $meta, $queryRelatedModules);
		$this->assertEquals("select count(*)  FROM vtiger_account  INNER JOIN vtiger_crmentity ON vtiger_account.accountid = vtiger_crmentity.crmid LEFT JOIN vtiger_account AS vtiger_accountaccount_id ON vtiger_accountaccount_id.accountid=vtiger_account.parentid   WHERE vtiger_crmentity.deleted=0 AND   ((vtiger_accountaccount_id.accountname IS NULL OR vtiger_accountaccount_id.accountname = '') ) AND vtiger_account.accountid > 0 ", $actual);
	}

	public function testUsers() {
		$actual = self::$vtModuleOperation->wsVTQL2SQL("select id, account_no, accountname, accounts.accountname from accounts where assigned_user_id !='cbTest testtz' and cf_729 = 'one' limit 0, 10;", $meta, $queryRelatedModules);
		$this->assertEquals(
			"select vtiger_account.accountid, vtiger_account.account_no, vtiger_account.accountname, vtiger_accountaccount_id.accountname as accountsaccountname, vtiger_account.accountid  FROM vtiger_account  INNER JOIN vtiger_crmentity ON vtiger_account.accountid = vtiger_crmentity.crmid LEFT JOIN vtiger_users ON vtiger_crmentity.smownerid = vtiger_users.id LEFT JOIN vtiger_groups ON vtiger_crmentity.smownerid = vtiger_groups.groupid INNER JOIN vtiger_accountscf ON vtiger_account.accountid = vtiger_accountscf.accountid LEFT JOIN vtiger_account AS vtiger_accountaccount_id ON vtiger_accountaccount_id.accountid=vtiger_account.parentid   WHERE vtiger_crmentity.deleted=0 AND   (( (trim(CONCAT(vtiger_users.first_name,' ',vtiger_users.last_name)) <> 'cbTest testtz' or vtiger_groups.groupname <> 'cbTest testtz'))  AND ( vtiger_accountscf.cf_729 IN (
								select translation_key
								from vtiger_cbtranslation
								where locale=\"en_us\" and forpicklist=\"Accounts::cf_729\" and i18n = 'one') OR vtiger_accountscf.cf_729 = 'one') ) AND vtiger_account.accountid > 0  limit 0, 10 ",
			$actual
		);
		$actual = self::$vtModuleOperation->wsVTQL2SQL("select id, account_no, accountname, accounts.accountname from accounts where assigned_user_id='cbTest testtz' and cf_729='one' limit 0, 100;", $meta, $queryRelatedModules);
		$this->assertEquals(
			"select vtiger_account.accountid, vtiger_account.account_no, vtiger_account.accountname, vtiger_accountaccount_id.accountname as accountsaccountname, vtiger_account.accountid  FROM vtiger_account  INNER JOIN vtiger_crmentity ON vtiger_account.accountid = vtiger_crmentity.crmid LEFT JOIN vtiger_users ON vtiger_crmentity.smownerid = vtiger_users.id LEFT JOIN vtiger_groups ON vtiger_crmentity.smownerid = vtiger_groups.groupid INNER JOIN vtiger_accountscf ON vtiger_account.accountid = vtiger_accountscf.accountid LEFT JOIN vtiger_account AS vtiger_accountaccount_id ON vtiger_accountaccount_id.accountid=vtiger_account.parentid   WHERE vtiger_crmentity.deleted=0 AND   (( (trim(CONCAT(vtiger_users.first_name,' ',vtiger_users.last_name)) = 'cbTest testtz' or vtiger_groups.groupname = 'cbTest testtz'))  AND ( vtiger_accountscf.cf_729 IN (
								select translation_key
								from vtiger_cbtranslation
								where locale=\"en_us\" and forpicklist=\"Accounts::cf_729\" and i18n = 'one') OR vtiger_accountscf.cf_729 = 'one') ) AND vtiger_account.accountid > 0  limit 0, 100 ",
			$actual
		);
		$actual = self::$vtModuleOperation->wsVTQL2SQL("select id, account_no, accountname, accounts.accountname from accounts where Users.id=20x21199 and cf_729='one' limit 0, 100;", $meta, $queryRelatedModules);
		$this->assertEquals(
			"select vtiger_account.accountid, vtiger_account.account_no, vtiger_account.accountname, vtiger_accountaccount_id.accountname as accountsaccountname, vtiger_account.accountid  FROM vtiger_account  INNER JOIN vtiger_crmentity ON vtiger_account.accountid = vtiger_crmentity.crmid INNER JOIN vtiger_accountscf ON vtiger_account.accountid = vtiger_accountscf.accountid LEFT JOIN vtiger_users ON vtiger_users.id = vtiger_crmentity.smownerid  LEFT JOIN vtiger_groups ON vtiger_groups.groupid = vtiger_crmentity.smownerid  LEFT JOIN vtiger_account AS vtiger_accountaccount_id ON vtiger_accountaccount_id.accountid=vtiger_account.parentid   WHERE vtiger_crmentity.deleted=0 AND   ((vtiger_users.id = '21199' or vtiger_groups.groupid = '21199')  AND ( vtiger_accountscf.cf_729 IN (
								select translation_key
								from vtiger_cbtranslation
								where locale=\"en_us\" and forpicklist=\"Accounts::cf_729\" and i18n = 'one') OR vtiger_accountscf.cf_729 = 'one') ) AND vtiger_account.accountid > 0  limit 0, 100 ",
			$actual
		);
	}

	public function testRelations() {
		global $GetRelatedList_ReturnOnlyQuery;
		$GetRelatedList_ReturnOnlyQuery = true;
		$actual = self::$vtModuleOperation->wsVTQL2SQL("select potentialname,Accounts.accountname from Potentials;", $meta, $queryRelatedModules);
		$this->assertEquals("select vtiger_potential.potentialname, vtiger_accountrelated_to.accountname as accountsaccountname, vtiger_potential.potentialid  FROM vtiger_potential  INNER JOIN vtiger_crmentity ON vtiger_potential.potentialid = vtiger_crmentity.crmid LEFT JOIN vtiger_account AS vtiger_accountrelated_to ON vtiger_accountrelated_to.accountid=vtiger_potential.related_to   WHERE vtiger_crmentity.deleted=0 AND vtiger_potential.potentialid > 0 ", $actual);
		$actual = self::$vtModuleOperation->wsVTQL2SQL("select potentialname,Accounts.accountname,Contacts.lastname from Potentials;", $meta, $queryRelatedModules);
		$this->assertEquals("select vtiger_potential.potentialname, vtiger_accountrelated_to.accountname as accountsaccountname, vtiger_contactdetailsrelated_to.lastname as contactslastname, vtiger_potential.potentialid  FROM vtiger_potential  INNER JOIN vtiger_crmentity ON vtiger_potential.potentialid = vtiger_crmentity.crmid LEFT JOIN vtiger_account AS vtiger_accountrelated_to ON vtiger_accountrelated_to.accountid=vtiger_potential.related_to LEFT JOIN vtiger_contactdetails AS vtiger_contactdetailsrelated_to ON vtiger_contactdetailsrelated_to.contactid=vtiger_potential.related_to   WHERE vtiger_crmentity.deleted=0 AND vtiger_potential.potentialid > 0 ", $actual);
		$actual = self::$vtModuleOperation->wsVTQL2SQL("select ticket_title,Accounts.accountname,Contacts.lastname from HelpDesk;", $meta, $queryRelatedModules);
		$this->assertEquals("select vtiger_troubletickets.title, vtiger_accountparent_id.accountname as accountsaccountname, vtiger_contactdetailsparent_id.lastname as contactslastname, vtiger_troubletickets.ticketid  FROM vtiger_troubletickets  INNER JOIN vtiger_crmentity ON vtiger_troubletickets.ticketid = vtiger_crmentity.crmid LEFT JOIN vtiger_account AS vtiger_accountparent_id ON vtiger_accountparent_id.accountid=vtiger_troubletickets.parent_id LEFT JOIN vtiger_contactdetails AS vtiger_contactdetailsparent_id ON vtiger_contactdetailsparent_id.contactid=vtiger_troubletickets.parent_id   WHERE vtiger_crmentity.deleted=0 AND vtiger_troubletickets.ticketid > 0 ", $actual);
		$actual = self::$vtModuleOperation->wsVTQL2SQL("select * from projecttask where related.project='33x6613';", $meta, $queryRelatedModules);
		$this->assertEquals(
			"select vtiger_projecttask.projecttaskname,vtiger_projecttask.projecttask_no,vtiger_projecttask.projecttaskpriority,vtiger_projecttask.projecttasktype,vtiger_projecttask.projecttasknumber,vtiger_projecttask.projectid,vtiger_crmentity.smownerid as assigned_user_id,vtiger_crmentity.smownerid,vtiger_users.first_name as owner_firstname, vtiger_users.last_name as owner_lastname,vtiger_projecttask.email,vtiger_projecttask.projecttaskstatus,vtiger_crmentity.smcreatorid as creator,vtiger_crmentity.smcreatorid,vtiger_users.first_name as creator_firstname, vtiger_users.last_name as creator_lastname,vtiger_projecttask.projecttaskprogress,vtiger_projecttask.projecttaskhours,vtiger_projecttask.startdate,vtiger_projecttask.enddate,vtiger_crmentity.createdtime,vtiger_crmentity.modifiedtime,vtiger_crmentity.modifiedby,vtiger_crmentity.description,vtiger_projecttask.projecttaskid  FROM vtiger_projecttask INNER JOIN vtiger_crmentity ON vtiger_crmentity.crmid = vtiger_projecttask.projecttaskid INNER JOIN vtiger_projecttaskcf ON vtiger_projecttaskcf.projecttaskid = vtiger_projecttask.projecttaskid INNER JOIN vtiger_project ON (vtiger_project.projectid = vtiger_projecttask.projectid) LEFT JOIN vtiger_users ON vtiger_users.id = vtiger_crmentity.smownerid LEFT JOIN vtiger_groups ON vtiger_groups.groupid = vtiger_crmentity.smownerid WHERE vtiger_crmentity.deleted = 0 AND vtiger_project.projectid = 6613",
			$actual
		);
		$actual = self::$vtModuleOperation->wsVTQL2SQL("select * from projecttask where related.project='33x6613' and projecttaskname='tttt';", $meta, $queryRelatedModules);
		$this->assertEquals("select vtiger_projecttask.projecttaskname,vtiger_projecttask.projecttask_no,vtiger_projecttask.projecttaskpriority,vtiger_projecttask.projecttasktype,vtiger_projecttask.projecttasknumber,vtiger_projecttask.projectid,vtiger_crmentity.smownerid as assigned_user_id,vtiger_crmentity.smownerid,vtiger_users.first_name as owner_firstname, vtiger_users.last_name as owner_lastname,vtiger_projecttask.email,vtiger_projecttask.projecttaskstatus,vtiger_crmentity.smcreatorid as creator,vtiger_crmentity.smcreatorid,vtiger_users.first_name as creator_firstname, vtiger_users.last_name as creator_lastname,vtiger_projecttask.projecttaskprogress,vtiger_projecttask.projecttaskhours,vtiger_projecttask.startdate,vtiger_projecttask.enddate,vtiger_crmentity.createdtime,vtiger_crmentity.modifiedtime,vtiger_crmentity.modifiedby,vtiger_crmentity.description,vtiger_projecttask.projecttaskid  FROM vtiger_projecttask INNER JOIN vtiger_crmentity ON vtiger_crmentity.crmid = vtiger_projecttask.projecttaskid INNER JOIN vtiger_projecttaskcf ON vtiger_projecttaskcf.projecttaskid = vtiger_projecttask.projecttaskid INNER JOIN vtiger_project ON (vtiger_project.projectid = vtiger_projecttask.projectid) LEFT JOIN vtiger_users ON vtiger_users.id = vtiger_crmentity.smownerid LEFT JOIN vtiger_groups ON vtiger_groups.groupid = vtiger_crmentity.smownerid WHERE vtiger_crmentity.deleted = 0 AND vtiger_project.projectid = 6613 and  projecttaskname='tttt' ", $actual);
		$actual = self::$vtModuleOperation->wsVTQL2SQL("select * from documents where related.accounts='11x75';", $meta, $queryRelatedModules);
		$this->assertEquals("select vtiger_notes.title,vtiger_notes.folderid,vtiger_notes.note_no,vtiger_crmentity.smownerid as assigned_user_id,vtiger_crmentity.smownerid,vtiger_users.first_name as owner_firstname, vtiger_users.last_name as owner_lastname,vtiger_crmentity.createdtime,vtiger_crmentity.modifiedtime,vtiger_crmentity.modifiedby,vtiger_crmentity.smcreatorid as creator,vtiger_crmentity.smcreatorid,vtiger_users.first_name as creator_firstname, vtiger_users.last_name as creator_lastname,vtiger_notes.notecontent,vtiger_notes.filelocationtype,vtiger_notes.filestatus,vtiger_notes.filesize,vtiger_notes.filetype,vtiger_notes.fileversion,vtiger_notes.filedownloadcount,vtiger_notes.notesid  from vtiger_notes inner join vtiger_senotesrel on vtiger_senotesrel.notesid= vtiger_notes.notesid left join vtiger_notescf ON vtiger_notescf.notesid= vtiger_notes.notesid inner join vtiger_crmentity on vtiger_crmentity.crmid= vtiger_notes.notesid and vtiger_crmentity.deleted=0 inner join vtiger_crmentity crm2 on crm2.crmid=vtiger_senotesrel.crmid LEFT JOIN vtiger_groups ON vtiger_groups.groupid = vtiger_crmentity.smownerid left join vtiger_seattachmentsrel on vtiger_seattachmentsrel.crmid =vtiger_notes.notesid left join vtiger_attachments on vtiger_seattachmentsrel.attachmentsid = vtiger_attachments.attachmentsid left join vtiger_users on vtiger_crmentity.smownerid= vtiger_users.id where  crm2.crmid=75 ", $actual);
		$actual = self::$vtModuleOperation->wsVTQL2SQL("select * from documents where filelocationtype='E' and related.contacts='12x1084';", $meta, $queryRelatedModules);
		$this->assertEquals("select vtiger_notes.title,vtiger_notes.folderid,vtiger_notes.note_no,vtiger_crmentity.smownerid as assigned_user_id,vtiger_crmentity.smownerid,vtiger_users.first_name as owner_firstname, vtiger_users.last_name as owner_lastname,vtiger_crmentity.createdtime,vtiger_crmentity.modifiedtime,vtiger_crmentity.modifiedby,vtiger_crmentity.smcreatorid as creator,vtiger_crmentity.smcreatorid,vtiger_users.first_name as creator_firstname, vtiger_users.last_name as creator_lastname,vtiger_notes.notecontent,vtiger_notes.filelocationtype,vtiger_notes.filestatus,vtiger_notes.filesize,vtiger_notes.filetype,vtiger_notes.fileversion,vtiger_notes.filedownloadcount,vtiger_notes.notesid  from vtiger_notes inner join vtiger_senotesrel on vtiger_senotesrel.notesid= vtiger_notes.notesid left join vtiger_notescf ON vtiger_notescf.notesid= vtiger_notes.notesid inner join vtiger_crmentity on vtiger_crmentity.crmid= vtiger_notes.notesid and vtiger_crmentity.deleted=0 inner join vtiger_crmentity crm2 on crm2.crmid=vtiger_senotesrel.crmid LEFT JOIN vtiger_groups ON vtiger_groups.groupid = vtiger_crmentity.smownerid left join vtiger_seattachmentsrel on vtiger_seattachmentsrel.crmid =vtiger_notes.notesid left join vtiger_attachments on vtiger_seattachmentsrel.attachmentsid = vtiger_attachments.attachmentsid left join vtiger_users on vtiger_crmentity.smownerid= vtiger_users.id where  filelocationtype='E' and crm2.crmid=1084 ", $actual);
		$actual = self::$vtModuleOperation->wsVTQL2SQL("select * from documents where (related.Contacts='12x1084') AND (filelocationtype LIKE '%I%') LIMIT 5;", $meta, $queryRelatedModules);
		$this->assertEquals("select vtiger_notes.title,vtiger_notes.folderid,vtiger_notes.note_no,vtiger_crmentity.smownerid as assigned_user_id,vtiger_crmentity.smownerid,vtiger_users.first_name as owner_firstname, vtiger_users.last_name as owner_lastname,vtiger_crmentity.createdtime,vtiger_crmentity.modifiedtime,vtiger_crmentity.modifiedby,vtiger_crmentity.smcreatorid as creator,vtiger_crmentity.smcreatorid,vtiger_users.first_name as creator_firstname, vtiger_users.last_name as creator_lastname,vtiger_notes.notecontent,vtiger_notes.filelocationtype,vtiger_notes.filestatus,vtiger_notes.filesize,vtiger_notes.filetype,vtiger_notes.fileversion,vtiger_notes.filedownloadcount,vtiger_notes.notesid  from vtiger_notes inner join vtiger_senotesrel on vtiger_senotesrel.notesid= vtiger_notes.notesid left join vtiger_notescf ON vtiger_notescf.notesid= vtiger_notes.notesid inner join vtiger_crmentity on vtiger_crmentity.crmid= vtiger_notes.notesid and vtiger_crmentity.deleted=0 inner join vtiger_crmentity crm2 on crm2.crmid=vtiger_senotesrel.crmid LEFT JOIN vtiger_groups ON vtiger_groups.groupid = vtiger_crmentity.smownerid left join vtiger_seattachmentsrel on vtiger_seattachmentsrel.crmid =vtiger_notes.notesid left join vtiger_attachments on vtiger_seattachmentsrel.attachmentsid = vtiger_attachments.attachmentsid left join vtiger_users on vtiger_crmentity.smownerid= vtiger_users.id where  crm2.crmid=1084 AND (filelocationtype LIKE '%I%') LIMIT 5 ", $actual);
		$actual = self::$vtModuleOperation->wsVTQL2SQL("select * from documents where ( related.Contacts='12x1084' or crm2.crmid='11x75') AND (filelocationtype LIKE '%I%') LIMIT 5;", $meta, $queryRelatedModules);
		$this->assertEquals("select vtiger_notes.title,vtiger_notes.folderid,vtiger_notes.note_no,vtiger_crmentity.smownerid as assigned_user_id,vtiger_crmentity.smownerid,vtiger_users.first_name as owner_firstname, vtiger_users.last_name as owner_lastname,vtiger_crmentity.createdtime,vtiger_crmentity.modifiedtime,vtiger_crmentity.modifiedby,vtiger_crmentity.smcreatorid as creator,vtiger_crmentity.smcreatorid,vtiger_users.first_name as creator_firstname, vtiger_users.last_name as creator_lastname,vtiger_notes.notecontent,vtiger_notes.filelocationtype,vtiger_notes.filestatus,vtiger_notes.filesize,vtiger_notes.filetype,vtiger_notes.fileversion,vtiger_notes.filedownloadcount,vtiger_notes.notesid  from vtiger_notes inner join vtiger_senotesrel on vtiger_senotesrel.notesid= vtiger_notes.notesid left join vtiger_notescf ON vtiger_notescf.notesid= vtiger_notes.notesid inner join vtiger_crmentity on vtiger_crmentity.crmid= vtiger_notes.notesid and vtiger_crmentity.deleted=0 inner join vtiger_crmentity crm2 on crm2.crmid=vtiger_senotesrel.crmid LEFT JOIN vtiger_groups ON vtiger_groups.groupid = vtiger_crmentity.smownerid left join vtiger_seattachmentsrel on vtiger_seattachmentsrel.crmid =vtiger_notes.notesid left join vtiger_attachments on vtiger_seattachmentsrel.attachmentsid = vtiger_attachments.attachmentsid left join vtiger_users on vtiger_crmentity.smownerid= vtiger_users.id where  ( crm2.crmid=1084 or crm2.crmid = 75 ) AND (filelocationtype LIKE '%I%') LIMIT 5 ", $actual);
		$actual = self::$vtModuleOperation->wsVTQL2SQL("select * from documents where (related.Contacts='12x1084' or crm2.crmid='11x75') AND (filelocationtype LIKE '%I%') LIMIT 5;", $meta, $queryRelatedModules);
		$this->assertEquals("select vtiger_notes.title,vtiger_notes.folderid,vtiger_notes.note_no,vtiger_crmentity.smownerid as assigned_user_id,vtiger_crmentity.smownerid,vtiger_users.first_name as owner_firstname, vtiger_users.last_name as owner_lastname,vtiger_crmentity.createdtime,vtiger_crmentity.modifiedtime,vtiger_crmentity.modifiedby,vtiger_crmentity.smcreatorid as creator,vtiger_crmentity.smcreatorid,vtiger_users.first_name as creator_firstname, vtiger_users.last_name as creator_lastname,vtiger_notes.notecontent,vtiger_notes.filelocationtype,vtiger_notes.filestatus,vtiger_notes.filesize,vtiger_notes.filetype,vtiger_notes.fileversion,vtiger_notes.filedownloadcount,vtiger_notes.notesid  from vtiger_notes inner join vtiger_senotesrel on vtiger_senotesrel.notesid= vtiger_notes.notesid left join vtiger_notescf ON vtiger_notescf.notesid= vtiger_notes.notesid inner join vtiger_crmentity on vtiger_crmentity.crmid= vtiger_notes.notesid and vtiger_crmentity.deleted=0 inner join vtiger_crmentity crm2 on crm2.crmid=vtiger_senotesrel.crmid LEFT JOIN vtiger_groups ON vtiger_groups.groupid = vtiger_crmentity.smownerid left join vtiger_seattachmentsrel on vtiger_seattachmentsrel.crmid =vtiger_notes.notesid left join vtiger_attachments on vtiger_seattachmentsrel.attachmentsid = vtiger_attachments.attachmentsid left join vtiger_users on vtiger_crmentity.smownerid= vtiger_users.id where  (crm2.crmid=1084 or crm2.crmid = 75 ) AND (filelocationtype LIKE '%I%') LIMIT 5 ", $actual);
		$actual = self::$vtModuleOperation->wsVTQL2SQL("select * from modcomments where related.helpdesk='17x2636';", $meta, $queryRelatedModules);
		$this->assertEquals("select
						concat(case when (ownertype = 'user') then '19x' else '12x' end,ownerid) as creator,
						concat(case when (ownertype = 'user') then '19x' else '12x' end,ownerid) as assigned_user_id,
						'TicketComments' as setype,
						createdtime,
						createdtime as modifiedtime,
						0 as id,
						comments as commentcontent,
						'17x2636' as related_to,
						'' as parent_comments,
						ownertype,
						case when (ownertype = 'user') then vtiger_users.user_name else vtiger_portalinfo.user_name end as owner_name
					 from vtiger_ticketcomments
					 left join vtiger_users on vtiger_users.id = ownerid
					 left join vtiger_portalinfo on vtiger_portalinfo.id = ownerid
					 where ticketid=2636", $actual);
		$actual = self::$vtModuleOperation->wsVTQL2SQL("select * from modcomments where related.helpdesk='17x2636' and commentcontent like 'hdcc%';", $meta, $queryRelatedModules);
		$this->assertEquals("select
						concat(case when (ownertype = 'user') then '19x' else '12x' end,ownerid) as creator,
						concat(case when (ownertype = 'user') then '19x' else '12x' end,ownerid) as assigned_user_id,
						'TicketComments' as setype,
						createdtime,
						createdtime as modifiedtime,
						0 as id,
						comments as commentcontent,
						'17x2636' as related_to,
						'' as parent_comments,
						ownertype,
						case when (ownertype = 'user') then vtiger_users.user_name else vtiger_portalinfo.user_name end as owner_name
					 from vtiger_ticketcomments
					 left join vtiger_users on vtiger_users.id = ownerid
					 left join vtiger_portalinfo on vtiger_portalinfo.id = ownerid
					 where ticketid=2636 and  comments like 'hdcc%' ", $actual);
		$actual = self::$vtModuleOperation->wsVTQL2SQL("select productname from products where related.products='14x2616';", $meta, $queryRelatedModules);
		$this->assertEquals("select vtiger_products.productname  FROM vtiger_productcomponent INNER JOIN vtiger_productcomponentcf ON vtiger_productcomponentcf.productcomponentid = vtiger_productcomponent.productcomponentid INNER JOIN vtiger_crmentity ON vtiger_crmentity.crmid = vtiger_productcomponent.productcomponentid INNER JOIN vtiger_products on vtiger_products.productid=vtiger_productcomponent.topdo INNER JOIN vtiger_crmentity cpdo ON cpdo.crmid = vtiger_products.productid LEFT JOIN vtiger_users ON vtiger_users.id=vtiger_crmentity.smownerid LEFT JOIN vtiger_groups ON vtiger_groups.groupid = vtiger_crmentity.smownerid inner join vtiger_products on vtiger_products.productid=vtiger_productcomponent.topdo WHERE vtiger_crmentity.deleted = 0 AND cpdo.deleted = 0 AND vtiger_productcomponent.frompdo = 2616", $actual);
		$actual = self::$vtModuleOperation->wsVTQL2SQL("select productname from products where related.contacts='12x1084';", $meta, $queryRelatedModules);
		$this->assertEquals("select vtiger_products.productname FROM vtiger_products INNER JOIN vtiger_seproductsrel ON vtiger_seproductsrel.productid=vtiger_products.productid and vtiger_seproductsrel.setype=\"Contacts\" INNER JOIN vtiger_productcf ON vtiger_products.productid = vtiger_productcf.productid INNER JOIN vtiger_crmentity ON vtiger_crmentity.crmid = vtiger_products.productid INNER JOIN vtiger_contactdetails ON vtiger_contactdetails.contactid = vtiger_seproductsrel.crmid LEFT JOIN vtiger_users ON vtiger_users.id=vtiger_crmentity.smownerid LEFT JOIN vtiger_groups ON vtiger_groups.groupid = vtiger_crmentity.smownerid left join vtiger_producttaxrel on vtiger_producttaxrel.productid = vtiger_products.productid  WHERE vtiger_contactdetails.contactid = 1084 and vtiger_crmentity.deleted = 0", $actual);
		$actual = self::$vtModuleOperation->wsVTQL2SQL("select productname from products where related.contacts='12x1084' and productcategory='Software';", $meta, $queryRelatedModules);
		$this->assertEquals("select vtiger_products.productname FROM vtiger_products INNER JOIN vtiger_seproductsrel ON vtiger_seproductsrel.productid=vtiger_products.productid and vtiger_seproductsrel.setype=\"Contacts\" INNER JOIN vtiger_productcf ON vtiger_products.productid = vtiger_productcf.productid INNER JOIN vtiger_crmentity ON vtiger_crmentity.crmid = vtiger_products.productid INNER JOIN vtiger_contactdetails ON vtiger_contactdetails.contactid = vtiger_seproductsrel.crmid LEFT JOIN vtiger_users ON vtiger_users.id=vtiger_crmentity.smownerid LEFT JOIN vtiger_groups ON vtiger_groups.groupid = vtiger_crmentity.smownerid left join vtiger_producttaxrel on vtiger_producttaxrel.productid = vtiger_products.productid  WHERE vtiger_contactdetails.contactid = 1084 and vtiger_crmentity.deleted = 0 and  productcategory='Software' ", $actual);
		$actual = self::$vtModuleOperation->wsVTQL2SQL("Select productname from Products where related.Contacts='12x1084' LIMIT 5;", $meta, $queryRelatedModules);
		$this->assertEquals("select vtiger_products.productname FROM vtiger_products INNER JOIN vtiger_seproductsrel ON vtiger_seproductsrel.productid=vtiger_products.productid and vtiger_seproductsrel.setype=\"Contacts\" INNER JOIN vtiger_productcf ON vtiger_products.productid = vtiger_productcf.productid INNER JOIN vtiger_crmentity ON vtiger_crmentity.crmid = vtiger_products.productid INNER JOIN vtiger_contactdetails ON vtiger_contactdetails.contactid = vtiger_seproductsrel.crmid LEFT JOIN vtiger_users ON vtiger_users.id=vtiger_crmentity.smownerid LEFT JOIN vtiger_groups ON vtiger_groups.groupid = vtiger_crmentity.smownerid left join vtiger_producttaxrel on vtiger_producttaxrel.productid = vtiger_products.productid  WHERE vtiger_contactdetails.contactid = 1084 and vtiger_crmentity.deleted = 0 LIMIT 5 ", $actual);
		$actual = self::$vtModuleOperation->wsVTQL2SQL("Select productname from Products where related.Contacts='12x1084' order by productname LIMIT 5;", $meta, $queryRelatedModules);
		$this->assertEquals("select vtiger_products.productname FROM vtiger_products INNER JOIN vtiger_seproductsrel ON vtiger_seproductsrel.productid=vtiger_products.productid and vtiger_seproductsrel.setype=\"Contacts\" INNER JOIN vtiger_productcf ON vtiger_products.productid = vtiger_productcf.productid INNER JOIN vtiger_crmentity ON vtiger_crmentity.crmid = vtiger_products.productid INNER JOIN vtiger_contactdetails ON vtiger_contactdetails.contactid = vtiger_seproductsrel.crmid LEFT JOIN vtiger_users ON vtiger_users.id=vtiger_crmentity.smownerid LEFT JOIN vtiger_groups ON vtiger_groups.groupid = vtiger_crmentity.smownerid left join vtiger_producttaxrel on vtiger_producttaxrel.productid = vtiger_products.productid  WHERE vtiger_contactdetails.contactid = 1084 and vtiger_crmentity.deleted = 0 order by vtiger_products.productname LIMIT 5 ", $actual);

		$actual = self::$vtModuleOperation->wsVTQL2SQL("select Contacts.firstname,Salesorder.subject,amount,paid from cobropago where Contacts.homephone='902886938';", $meta, $queryRelatedModules);
		$this->assertEquals("select vtiger_contactdetailsparent_id.firstname as contactsfirstname, vtiger_salesorderrelated_id.subject as salesordersubject, vtiger_cobropago.amount, vtiger_cobropago.paid, vtiger_cobropago.cobropagoid  FROM vtiger_cobropago  INNER JOIN vtiger_crmentity ON vtiger_cobropago.cobropagoid = vtiger_crmentity.crmid LEFT JOIN vtiger_contactsubdetails AS vtiger_contactsubdetailsparent_id ON vtiger_contactsubdetailsparent_id.contactsubscriptionid=vtiger_cobropago.parent_id LEFT JOIN vtiger_contactdetails AS vtiger_contactdetailsparent_id ON vtiger_contactdetailsparent_id.contactid=vtiger_cobropago.parent_id LEFT JOIN vtiger_salesorder AS vtiger_salesorderrelated_id ON vtiger_salesorderrelated_id.salesorderid=vtiger_cobropago.related_id   WHERE vtiger_crmentity.deleted=0 AND   ((vtiger_contactsubdetailsparent_id.homephone = '902886938') ) AND vtiger_cobropago.cobropagoid > 0 ", $actual);
		$actual = self::$vtModuleOperation->wsVTQL2SQL("select Products.productname,ticket_title from HelpDesk where Products.productname >= 'áé íÑÇ';", $meta, $queryRelatedModules);
		$this->assertEquals("select vtiger_productsproduct_id.productname as productsproductname, vtiger_troubletickets.title, vtiger_troubletickets.ticketid  FROM vtiger_troubletickets  INNER JOIN vtiger_crmentity ON vtiger_troubletickets.ticketid = vtiger_crmentity.crmid LEFT JOIN vtiger_products AS vtiger_productsproduct_id ON vtiger_productsproduct_id.productid=vtiger_troubletickets.product_id   WHERE vtiger_crmentity.deleted=0 AND   ((vtiger_productsproduct_id.productname >= 'áé íÑÇ') ) AND vtiger_troubletickets.ticketid > 0 ", $actual);
		$actual = self::$vtModuleOperation->wsVTQL2SQL("select Products.productname,ticket_title from HelpDesk where Products.productname > = 'áé íÑÇ';", $meta, $queryRelatedModules);
		$this->assertEquals("select vtiger_productsproduct_id.productname as productsproductname, vtiger_troubletickets.title, vtiger_troubletickets.ticketid  FROM vtiger_troubletickets  INNER JOIN vtiger_crmentity ON vtiger_troubletickets.ticketid = vtiger_crmentity.crmid LEFT JOIN vtiger_products AS vtiger_productsproduct_id ON vtiger_productsproduct_id.productid=vtiger_troubletickets.product_id   WHERE vtiger_crmentity.deleted=0 AND   ((vtiger_productsproduct_id.productname >= 'áé íÑÇ') ) AND vtiger_troubletickets.ticketid > 0 ", $actual);
		$actual = self::$vtModuleOperation->wsVTQL2SQL("select Products.productname,ticket_title from HelpDesk where Products.productname > = 'áé> =íÑÇ';", $meta, $queryRelatedModules);
		$this->assertEquals("select vtiger_productsproduct_id.productname as productsproductname, vtiger_troubletickets.title, vtiger_troubletickets.ticketid  FROM vtiger_troubletickets  INNER JOIN vtiger_crmentity ON vtiger_troubletickets.ticketid = vtiger_crmentity.crmid LEFT JOIN vtiger_products AS vtiger_productsproduct_id ON vtiger_productsproduct_id.productid=vtiger_troubletickets.product_id   WHERE vtiger_crmentity.deleted=0 AND   ((vtiger_productsproduct_id.productname >= 'áé> =íÑÇ') ) AND vtiger_troubletickets.ticketid > 0 ", $actual);
		$actual = self::$vtModuleOperation->wsVTQL2SQL("select Products.productname,ticket_title from HelpDesk where Products.productname   >   =   'áé     íÑÇ';", $meta, $queryRelatedModules);
		$this->assertEquals("select vtiger_productsproduct_id.productname as productsproductname, vtiger_troubletickets.title, vtiger_troubletickets.ticketid  FROM vtiger_troubletickets  INNER JOIN vtiger_crmentity ON vtiger_troubletickets.ticketid = vtiger_crmentity.crmid LEFT JOIN vtiger_products AS vtiger_productsproduct_id ON vtiger_productsproduct_id.productid=vtiger_troubletickets.product_id   WHERE vtiger_crmentity.deleted=0 AND   ((vtiger_productsproduct_id.productname >= 'áé     íÑÇ') ) AND vtiger_troubletickets.ticketid > 0 ", $actual);
		$actual = self::$vtModuleOperation->wsVTQL2SQL("select Products.productname,ticket_title from HelpDesk where Products.productname   >   =   'áé>    =íÑÇ';", $meta, $queryRelatedModules);
		$this->assertEquals("select vtiger_productsproduct_id.productname as productsproductname, vtiger_troubletickets.title, vtiger_troubletickets.ticketid  FROM vtiger_troubletickets  INNER JOIN vtiger_crmentity ON vtiger_troubletickets.ticketid = vtiger_crmentity.crmid LEFT JOIN vtiger_products AS vtiger_productsproduct_id ON vtiger_productsproduct_id.productid=vtiger_troubletickets.product_id   WHERE vtiger_crmentity.deleted=0 AND   ((vtiger_productsproduct_id.productname >= 'áé>    =íÑÇ') ) AND vtiger_troubletickets.ticketid > 0 ", $actual);

		$actual = self::$vtModuleOperation->wsVTQL2SQL("select potentialname from Potentials where related_to='11x75';", $meta, $queryRelatedModules);
		$this->assertEquals("SELECT vtiger_potential.potentialname,vtiger_potential.potentialid FROM vtiger_potential LEFT JOIN vtiger_crmentity ON vtiger_potential.potentialid=vtiger_crmentity.crmid   WHERE (vtiger_potential.related_to = 75) AND  vtiger_crmentity.deleted=0 LIMIT 100;", $actual);
		$actual = self::$vtModuleOperation->wsVTQL2SQL("select potentialname,Campaigns.campaignname from Potentials where related_to='Chemex Labs Ltd';", $meta, $queryRelatedModules);
		$this->assertEquals("select vtiger_potential.potentialname, vtiger_campaigncampaignid.campaignname as campaignscampaignname, vtiger_potential.potentialid  FROM vtiger_potential  INNER JOIN vtiger_crmentity ON vtiger_potential.potentialid = vtiger_crmentity.crmid LEFT JOIN vtiger_account  ON vtiger_potential.related_to = vtiger_account.accountid LEFT JOIN vtiger_contactdetails  ON vtiger_potential.related_to = vtiger_contactdetails.contactid LEFT JOIN vtiger_campaign AS vtiger_campaigncampaignid ON vtiger_campaigncampaignid.campaignid=vtiger_potential.campaignid   WHERE vtiger_crmentity.deleted=0 AND   (( trim(vtiger_account.accountname) = 'Chemex Labs Ltd' OR trim(CONCAT(vtiger_contactdetails.firstname,' ',vtiger_contactdetails.lastname)) = 'Chemex Labs Ltd') ) AND vtiger_potential.potentialid > 0 ", $actual);
		$actual = self::$vtModuleOperation->wsVTQL2SQL("select potentialname,Campaigns.campaignname from Potentials where Accounts.id='11x75';", $meta, $queryRelatedModules);
		$this->assertEquals("select vtiger_potential.potentialname, vtiger_campaigncampaignid.campaignname as campaignscampaignname, vtiger_potential.potentialid  FROM vtiger_potential  INNER JOIN vtiger_crmentity ON vtiger_potential.potentialid = vtiger_crmentity.crmid LEFT JOIN vtiger_account AS vtiger_accountrelated_to ON vtiger_accountrelated_to.accountid=vtiger_potential.related_to LEFT JOIN vtiger_campaign AS vtiger_campaigncampaignid ON vtiger_campaigncampaignid.campaignid=vtiger_potential.campaignid   WHERE vtiger_crmentity.deleted=0 AND   ((vtiger_accountrelated_to.accountid = '75') ) AND vtiger_potential.potentialid > 0 ", $actual);
		$actual = self::$vtModuleOperation->wsVTQL2SQL("select potentialname,Campaigns.campaignname from Potentials where Accounts.id='11x75' or Contacts.id='12x1084';", $meta, $queryRelatedModules);
		$this->assertEquals("select vtiger_potential.potentialname, vtiger_campaigncampaignid.campaignname as campaignscampaignname, vtiger_potential.potentialid  FROM vtiger_potential  INNER JOIN vtiger_crmentity ON vtiger_potential.potentialid = vtiger_crmentity.crmid LEFT JOIN vtiger_account AS vtiger_accountrelated_to ON vtiger_accountrelated_to.accountid=vtiger_potential.related_to LEFT JOIN vtiger_contactdetails AS vtiger_contactdetailsrelated_to ON vtiger_contactdetailsrelated_to.contactid=vtiger_potential.related_to LEFT JOIN vtiger_campaign AS vtiger_campaigncampaignid ON vtiger_campaigncampaignid.campaignid=vtiger_potential.campaignid   WHERE vtiger_crmentity.deleted=0 AND   ((vtiger_accountrelated_to.accountid = '75')  OR (vtiger_contactdetailsrelated_to.contactid = '1084') ) AND vtiger_potential.potentialid > 0 ", $actual);

		$GetRelatedList_ReturnOnlyQuery = false;
	}

	public function testIncorrectModule() {
		$this->expectException('WebServiceException');
		$actual = self::$vtModuleOperation->wsVTQL2SQL('select nofield from NoModule;', $meta, $queryRelatedModules);
	}

	public function testIncorrectField() {
		$this->expectException('WebServiceException');
		$actual = self::$vtModuleOperation->wsVTQL2SQL('select accountname,nofield from Accounts;', $meta, $queryRelatedModules);
	}

	public function testIncorrectQuote() {
		$this->expectException('Exception');
		$actual = self::$vtModuleOperation->wsVTQL2SQL('select id,firstname,lastname from Contacts where firstname LIKE "%ele%" LIMIT 0, 250;', $meta, $queryRelatedModules);
	}

	public function testExtendedConditionQuery() {
		$actual = self::$vtModuleOperation->wsVTQL2SQL('select id, account_no, accountname, Accounts.accountname from accounts where [{"fieldname":"assigned_user_id","operation":"is","value":"cbTest testtz","valuetype":"raw","joincondition":"and","groupid":"0"}]', $meta, $queryRelatedModules);
		$this->assertEquals(
			"SELECT vtiger_account.accountid, vtiger_account.accountid, vtiger_account.account_no, vtiger_account.accountname, vtiger_accountaccount_id.accountname as accountsaccountname FROM vtiger_account  INNER JOIN vtiger_crmentity ON vtiger_account.accountid = vtiger_crmentity.crmid LEFT JOIN vtiger_users ON vtiger_crmentity.smownerid = vtiger_users.id LEFT JOIN vtiger_groups ON vtiger_crmentity.smownerid = vtiger_groups.groupid LEFT JOIN vtiger_account AS vtiger_accountaccount_id ON vtiger_accountaccount_id.accountid=vtiger_account.parentid  WHERE vtiger_crmentity.deleted=0 AND   (  (( (trim(CONCAT(vtiger_users.first_name,' ',vtiger_users.last_name)) = 'cbTest testtz' or vtiger_groups.groupname = 'cbTest testtz')) )) AND vtiger_account.accountid > 0",
			$actual
		);
		$actual = self::$vtModuleOperation->wsVTQL2SQL('select firstname from Leads where [{"fieldname":"firstname","operation":"greater than","value":"uppercase(lastname)","valuetype":"expression","joincondition":"and","groupid":"0"}];', $meta, $queryRelatedModules);
		$this->assertEquals(
			"SELECT vtiger_leaddetails.leadid, vtiger_leaddetails.firstname, vtiger_leaddetails.leadid FROM vtiger_leaddetails  INNER JOIN vtiger_crmentity ON vtiger_leaddetails.leadid = vtiger_crmentity.crmid  WHERE vtiger_crmentity.deleted=0 and vtiger_leaddetails.converted=0 AND   (  (( vtiger_leaddetails.firstname > UPPER(vtiger_leaddetails.lastname)) )) AND vtiger_leaddetails.leadid > 0",
			$actual
		);
		$actual = self::$vtModuleOperation->wsVTQL2SQL('select id, account_no, accountname, Accounts.accountname from accounts where [{"fieldname":"assigned_user_id","operation":"is","value":"cbTest testtz","valuetype":"raw","joincondition":"and","groupid":"0"}] order by account_no', $meta, $queryRelatedModules);
		$this->assertEquals(
			"SELECT vtiger_account.accountid, vtiger_account.accountid, vtiger_account.account_no, vtiger_account.accountname, vtiger_accountaccount_id.accountname as accountsaccountname FROM vtiger_account  INNER JOIN vtiger_crmentity ON vtiger_account.accountid = vtiger_crmentity.crmid LEFT JOIN vtiger_users ON vtiger_crmentity.smownerid = vtiger_users.id LEFT JOIN vtiger_groups ON vtiger_crmentity.smownerid = vtiger_groups.groupid LEFT JOIN vtiger_account AS vtiger_accountaccount_id ON vtiger_accountaccount_id.accountid=vtiger_account.parentid  WHERE vtiger_crmentity.deleted=0 AND   (  (( (trim(CONCAT(vtiger_users.first_name,' ',vtiger_users.last_name)) = 'cbTest testtz' or vtiger_groups.groupname = 'cbTest testtz')) )) AND vtiger_account.accountid > 0 order by account_no",
			$actual
		);
		$actual = self::$vtModuleOperation->wsVTQL2SQL('select id, account_no, accountname, Accounts.accountname from accounts where [{"fieldname":"assigned_user_id","operation":"is","value":"cbTest testtz","valuetype":"raw","joincondition":"and","groupid":"0"}] order by account_no desc limit 5', $meta, $queryRelatedModules);
		$this->assertEquals(
			"SELECT vtiger_account.accountid, vtiger_account.accountid, vtiger_account.account_no, vtiger_account.accountname, vtiger_accountaccount_id.accountname as accountsaccountname FROM vtiger_account  INNER JOIN vtiger_crmentity ON vtiger_account.accountid = vtiger_crmentity.crmid LEFT JOIN vtiger_users ON vtiger_crmentity.smownerid = vtiger_users.id LEFT JOIN vtiger_groups ON vtiger_crmentity.smownerid = vtiger_groups.groupid LEFT JOIN vtiger_account AS vtiger_accountaccount_id ON vtiger_accountaccount_id.accountid=vtiger_account.parentid  WHERE vtiger_crmentity.deleted=0 AND   (  (( (trim(CONCAT(vtiger_users.first_name,' ',vtiger_users.last_name)) = 'cbTest testtz' or vtiger_groups.groupname = 'cbTest testtz')) )) AND vtiger_account.accountid > 0 order by account_no desc limit 5",
			$actual
		);
		$actual = self::$vtModuleOperation->wsVTQL2SQL('select id, account_no, accountname, Accounts.accountname from accounts where [{"fieldname":"assigned_user_id","operation":"is","value":"cbTest testtz","valuetype":"raw","joincondition":"and","groupid":"0"}] limit 5', $meta, $queryRelatedModules);
		$this->assertEquals(
			"SELECT vtiger_account.accountid, vtiger_account.accountid, vtiger_account.account_no, vtiger_account.accountname, vtiger_accountaccount_id.accountname as accountsaccountname FROM vtiger_account  INNER JOIN vtiger_crmentity ON vtiger_account.accountid = vtiger_crmentity.crmid LEFT JOIN vtiger_users ON vtiger_crmentity.smownerid = vtiger_users.id LEFT JOIN vtiger_groups ON vtiger_crmentity.smownerid = vtiger_groups.groupid LEFT JOIN vtiger_account AS vtiger_accountaccount_id ON vtiger_accountaccount_id.accountid=vtiger_account.parentid  WHERE vtiger_crmentity.deleted=0 AND   (  (( (trim(CONCAT(vtiger_users.first_name,' ',vtiger_users.last_name)) = 'cbTest testtz' or vtiger_groups.groupname = 'cbTest testtz')) )) AND vtiger_account.accountid > 0 limit 5",
			$actual
		);
		$actual = self::$vtModuleOperation->wsVTQL2SQL('select [{"fieldname":"countres","operation":"is","value":"count(accountname)","valuetype":"expression","joincondition":"and","groupid":"0"}] from accounts where [{"fieldname":"assigned_user_id","operation":"is","value":"cbTest testtz","valuetype":"raw","joincondition":"and","groupid":"0"}] limit 5', $meta, $queryRelatedModules);
		$this->assertEquals(
			"SELECT COUNT(vtiger_account.accountname) AS countres FROM vtiger_account  INNER JOIN vtiger_crmentity ON vtiger_account.accountid = vtiger_crmentity.crmid LEFT JOIN vtiger_users ON vtiger_crmentity.smownerid = vtiger_users.id LEFT JOIN vtiger_groups ON vtiger_crmentity.smownerid = vtiger_groups.groupid  WHERE vtiger_crmentity.deleted=0 AND   (  (( (trim(CONCAT(vtiger_users.first_name,' ',vtiger_users.last_name)) = 'cbTest testtz' or vtiger_groups.groupname = 'cbTest testtz')) )) AND vtiger_account.accountid > 0 limit 5",
			$actual
		);
		$actual = self::$vtModuleOperation->wsVTQL2SQL('select [{"fieldname":"countres","operation":"is","value":"count(accountname)","valuetype":"expression","joincondition":"and","groupid":"0"}] from accounts', $meta, $queryRelatedModules);
		$this->assertEquals(
			"SELECT COUNT(vtiger_account.accountname) AS countres FROM vtiger_account  INNER JOIN vtiger_crmentity ON vtiger_account.accountid = vtiger_crmentity.crmid  WHERE vtiger_crmentity.deleted=0 AND vtiger_account.accountid > 0",
			$actual
		);
		$actual = self::$vtModuleOperation->wsVTQL2SQL('select [{"fieldname":"countres","operation":"is","value":"count(accountname)","valuetype":"expression","joincondition":"and","groupid":"0"}] from accounts order by 1', $meta, $queryRelatedModules);
		$this->assertEquals(
			"SELECT COUNT(vtiger_account.accountname) AS countres FROM vtiger_account  INNER JOIN vtiger_crmentity ON vtiger_account.accountid = vtiger_crmentity.crmid  WHERE vtiger_crmentity.deleted=0 AND vtiger_account.accountid > 0 order by 1",
			$actual
		);
	}

	public function testExtendedConditionGroupByQuery() {
		$actual = self::$vtModuleOperation->wsVTQL2SQL('select accountname from accounts where [{"fieldname":"assigned_user_id","operation":"is","value":"cbTest testtz","valuetype":"raw","joincondition":"and","groupid":"0"}] group by accountname', $meta, $queryRelatedModules);
		$this->assertEquals(
			"SELECT vtiger_account.accountname FROM vtiger_account  INNER JOIN vtiger_crmentity ON vtiger_account.accountid = vtiger_crmentity.crmid LEFT JOIN vtiger_users ON vtiger_crmentity.smownerid = vtiger_users.id LEFT JOIN vtiger_groups ON vtiger_crmentity.smownerid = vtiger_groups.groupid  WHERE vtiger_crmentity.deleted=0 AND   (  (( (trim(CONCAT(vtiger_users.first_name,' ',vtiger_users.last_name)) = 'cbTest testtz' or vtiger_groups.groupname = 'cbTest testtz')) )) AND vtiger_account.accountid > 0 group by accountname",
			$actual
		);
		$actual = self::$vtModuleOperation->wsVTQL2SQL('select [{"fieldname":"countres","operation":"is","value":"count(accountname)","valuetype":"expression","joincondition":"and","groupid":"0"}] from accounts group by 1;', $meta, $queryRelatedModules);
		$this->assertEquals(
			"SELECT COUNT(vtiger_account.accountname) AS countres FROM vtiger_account  INNER JOIN vtiger_crmentity ON vtiger_account.accountid = vtiger_crmentity.crmid  WHERE vtiger_crmentity.deleted=0 AND vtiger_account.accountid > 0 group by 1;",
			$actual
		);
		$actual = self::$vtModuleOperation->wsVTQL2SQL('select [{"fieldname":"countres","operation":"is","value":"count(accountname)","valuetype":"expression","joincondition":"and","groupid":"0"},{"fieldname":"bill_city","operation":"is","value":"bill_city","valuetype":"fieldname","joincondition":"and","groupid":"0"}] from accounts group by bill_city;', $meta, $queryRelatedModules);
		$this->assertEquals(
			"SELECT COUNT(vtiger_account.accountname) AS countres,vtiger_accountbillads.bill_city FROM vtiger_account  INNER JOIN vtiger_crmentity ON vtiger_account.accountid = vtiger_crmentity.crmid INNER JOIN vtiger_accountbillads ON vtiger_account.accountid = vtiger_accountbillads.accountaddressid  WHERE vtiger_crmentity.deleted=0 AND vtiger_account.accountid > 0 group by bill_city;",
			$actual
		);
		$actual = self::$vtModuleOperation->wsVTQL2SQL('select [{"fieldname":"countres","operation":"is","value":"count(accountname)","valuetype":"expression","joincondition":"and","groupid":"0"},{"fieldname":"bill_city","operation":"is","value":"bill_city","valuetype":"fieldname","joincondition":"and","groupid":"0"}] from accounts where [{"fieldname":"accountname","operation":"starts with","value":"a","valuetype":"raw","joincondition":"and","groupid":"0"}] group by bill_city;', $meta, $queryRelatedModules);
		$this->assertEquals(
			"SELECT COUNT(vtiger_account.accountname) AS countres,vtiger_accountbillads.bill_city FROM vtiger_account  INNER JOIN vtiger_crmentity ON vtiger_account.accountid = vtiger_crmentity.crmid INNER JOIN vtiger_accountbillads ON vtiger_account.accountid = vtiger_accountbillads.accountaddressid  WHERE vtiger_crmentity.deleted=0 AND   (  (( vtiger_account.accountname LIKE 'a%') )) AND vtiger_account.accountid > 0 group by bill_city",
			$actual
		);
		/////
		$actual = self::$vtModuleOperation->wsVTQL2SQL('select [{"fieldname":"countres","operation":"is","value":"count(ticket_title)","valuetype":"expression","joincondition":"and","groupid":"0"},{"fieldname":"ticketstatus","operation":"is","value":"ticketstatus","valuetype":"fieldname","joincondition":"and","groupid":"0"}] from HelpDesk group by status;', $meta, $queryRelatedModules);
		$this->assertEquals(
			"SELECT COUNT(vtiger_troubletickets.title) AS countres,vtiger_troubletickets.status FROM vtiger_troubletickets  INNER JOIN vtiger_crmentity ON vtiger_troubletickets.ticketid = vtiger_crmentity.crmid  WHERE vtiger_crmentity.deleted=0 AND vtiger_troubletickets.ticketid > 0 group by status;",
			$actual
		);
		global $current_user, $adb, $log;
		$holdUser = $current_user;
		$current_user->retrieveCurrentUserInfoFromFile(7); // testymd HelpDesk is private
		$webserviceObject = VtigerWebserviceObject::fromName($adb, 'HelpDesk');
		$vtModuleOperation = new VtigerModuleOperation($webserviceObject, $current_user, $adb, $log);
		$actual = $vtModuleOperation->wsVTQL2SQL('select [{"fieldname":"countres","operation":"is","value":"count(ticket_title)","valuetype":"expression","joincondition":"and","groupid":"0"},{"fieldname":"ticketstatus","operation":"is","value":"ticketstatus","valuetype":"fieldname","joincondition":"and","groupid":"0"}] from HelpDesk group by status;', $meta, $queryRelatedModules);
		$current_user = $holdUser;
		$this->assertEquals(
			"SELECT COUNT(vtiger_troubletickets.title) AS countres,vtiger_troubletickets.status FROM vtiger_troubletickets  INNER JOIN vtiger_crmentity ON vtiger_troubletickets.ticketid = vtiger_crmentity.crmid INNER JOIN vt_tmp_u7 vt_tmp_u7 ON vt_tmp_u7.id = vtiger_crmentity.smownerid  WHERE vtiger_crmentity.deleted=0 AND vtiger_troubletickets.ticketid > 0 group by status;",
			$actual
		);
	}

	public function testActorQuery() {
		$actual = self::$vtModuleOperation->wsVTQL2SQL('select * from Currency;', $meta, $queryRelatedModules);
		$this->assertEquals(
			"SELECT vtiger_currency_info.id,vtiger_currency_info.currency_name,vtiger_currency_info.currency_code,vtiger_currency_info.currency_symbol,vtiger_currency_info.conversion_rate,vtiger_currency_info.currency_status,vtiger_currency_info.defaultid,vtiger_currency_info.deleted,vtiger_currency_info.currency_position FROM vtiger_currency_info  WHERE  vtiger_currency_info.deleted=0 LIMIT 100;",
			$actual
		);
		$actual = self::$vtModuleOperation->wsVTQL2SQL('select * from Workflow;', $meta, $queryRelatedModules);
		$this->assertEquals(
			"SELECT com_vtiger_workflows.workflow_id,com_vtiger_workflows.module_name,com_vtiger_workflows.summary,com_vtiger_workflows.test,com_vtiger_workflows.execution_condition,com_vtiger_workflows.defaultworkflow,com_vtiger_workflows.type,com_vtiger_workflows.schtypeid,com_vtiger_workflows.schtime,com_vtiger_workflows.schdayofmonth,com_vtiger_workflows.schdayofweek,com_vtiger_workflows.schannualdates,com_vtiger_workflows.nexttrigger_time,com_vtiger_workflows.schminuteinterval,com_vtiger_workflows.purpose,com_vtiger_workflows.workflow_id FROM com_vtiger_workflows   LIMIT 100;",
			$actual
		);
		$actual = self::$vtModuleOperation->wsVTQL2SQL('select * from AuditTrail;', $meta, $queryRelatedModules);
		$this->assertEquals(
			"SELECT vtiger_audit_trial.auditid,vtiger_audit_trial.userid,vtiger_audit_trial.module,vtiger_audit_trial.action,vtiger_audit_trial.recordid,vtiger_audit_trial.actiondate,vtiger_audit_trial.auditid FROM vtiger_audit_trial   LIMIT 100;",
			$actual
		);
		$actual = self::$vtModuleOperation->wsVTQL2SQL('select * from LoginHistory;', $meta, $queryRelatedModules);
		$this->assertEquals(
			"SELECT vtiger_loginhistory.login_id,vtiger_loginhistory.user_name,vtiger_loginhistory.user_ip,vtiger_loginhistory.logout_time,vtiger_loginhistory.login_time,vtiger_loginhistory.status,vtiger_loginhistory.login_id FROM vtiger_loginhistory   LIMIT 100;",
			$actual
		);
	}
}
?>
