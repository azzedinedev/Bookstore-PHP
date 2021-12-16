<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 *                                   ATTENTION!
 * If you see this message in your browser (Internet Explorer, Mozilla Firefox, Google Chrome, etc.)
 * this means that PHP is not properly installed on your web server. Please refer to the PHP manual
 * for more details: http://php.net/manual/install.php 
 *
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 */

    include_once dirname(__FILE__) . '/components/startup.php';
    include_once dirname(__FILE__) . '/components/application.php';


    include_once dirname(__FILE__) . '/' . 'database_engine/mysql_engine.php';
    include_once dirname(__FILE__) . '/' . 'components/page/page.php';
    include_once dirname(__FILE__) . '/' . 'components/page/detail_page.php';
    include_once dirname(__FILE__) . '/' . 'components/page/nested_form_page.php';
    include_once dirname(__FILE__) . '/' . 'authorization.php';

    function GetConnectionOptions()
    {
        $result = GetGlobalConnectionOptions();
        $result['client_encoding'] = 'utf8';
        GetApplication()->GetUserAuthentication()->applyIdentityToConnectionOptions($result);
        return $result;
    }

    
    
    
    // OnBeforePageExecute event handler
    
    
    
    class order_historyPage extends Page
    {
        protected function DoBeforeCreate()
        {
            $this->dataset = new TableDataset(
                MySqlIConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '`order_history`');
            $field = new IntegerField('order_history_id', null, null, true);
            $field->SetIsNotNull(true);
            $this->dataset->AddField($field, true);
            $field = new StringField('status');
            $field->SetIsNotNull(true);
            $this->dataset->AddField($field, false);
            $field = new DateField('date');
            $this->dataset->AddField($field, false);
            $field = new IntegerField('order_id');
            $field->SetIsNotNull(true);
            $this->dataset->AddField($field, false);
            $this->dataset->AddLookupField('status', '(SELECT 0 AS F1, \'on process\' AS F2
            UNION ALL SELECT 1, \'on delivery\'
            UNION ALL SELECT 2, \'completed\')', new StringField('F1'), new StringField('F2', 'status_F2', 'status_F2_Query-selcetbox-order-status'), 'status_F2_Query-selcetbox-order-status');
            $this->dataset->AddLookupField('order_id', '(SELECT O.*, 
            CONCAT(\'#\',O.order_id, \' (\', U.firstname, \' \', U.lastname, \')\') as order_id_fullname,  
            CONCAT(U.firstname, \' \', U.lastname) as user_fullname, 
            U.firstname AS user_firstname, U.lastname AS user_lastname 
            FROM `order` AS O  
            INNER JOIN `user` AS U ON O.user_id = U.user_id)', new StringField('order_id'), new BlobField('order_id_fullname', 'order_id_order_id_fullname', 'order_id_order_id_fullname_Query_orders'), 'order_id_order_id_fullname_Query_orders');
        }
    
        protected function DoPrepare() {
    
        }
    
        protected function CreatePageNavigator()
        {
            $result = new CompositePageNavigator($this);
            
            $partitionNavigator = new PageNavigator('pnav', $this, $this->dataset);
            $partitionNavigator->SetRowsPerPage(20);
            $result->AddPageNavigator($partitionNavigator);
            
            return $result;
        }
    
        protected function CreateRssGenerator()
        {
            return null;
        }
    
        protected function setupCharts()
        {
    
        }
    
        protected function getFiltersColumns()
        {
            return array(
                new FilterColumn($this->dataset, 'order_history_id', 'order_history_id', 'Id'),
                new FilterColumn($this->dataset, 'status', 'status_F2', 'Status'),
                new FilterColumn($this->dataset, 'date', 'date', 'Date'),
                new FilterColumn($this->dataset, 'order_id', 'order_id_order_id_fullname', 'Order')
            );
        }
    
        protected function setupQuickFilter(QuickFilter $quickFilter, FixedKeysArray $columns)
        {
            $quickFilter
                ->addColumn($columns['order_history_id'])
                ->addColumn($columns['status'])
                ->addColumn($columns['date'])
                ->addColumn($columns['order_id']);
        }
    
        protected function setupColumnFilter(ColumnFilter $columnFilter)
        {
            $columnFilter
                ->setOptionsFor('status')
                ->setOptionsFor('date')
                ->setOptionsFor('order_id');
        }
    
        protected function setupFilterBuilder(FilterBuilder $filterBuilder, FixedKeysArray $columns)
        {
            $main_editor = new TextEdit('order_history_id_edit');
            
            $filterBuilder->addColumn(
                $columns['order_history_id'],
                array(
                    FilterConditionOperator::EQUALS => $main_editor,
                    FilterConditionOperator::DOES_NOT_EQUAL => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_BETWEEN => $main_editor,
                    FilterConditionOperator::IS_NOT_BETWEEN => $main_editor,
                    FilterConditionOperator::IS_BLANK => null,
                    FilterConditionOperator::IS_NOT_BLANK => null
                )
            );
            
            $main_editor = new AutocompleteComboBox('status_edit', $this->CreateLinkBuilder());
            $main_editor->setAllowClear(true);
            $main_editor->setMinimumInputLength(0);
            $main_editor->SetAllowNullValue(false);
            $main_editor->SetHandlerName('filter_builder_status_F2_search');
            
            $multi_value_select_editor = new RemoteMultiValueSelect('status', $this->CreateLinkBuilder());
            $multi_value_select_editor->SetHandlerName('filter_builder_status_F2_search');
            
            $text_editor = new TextEdit('status');
            
            $filterBuilder->addColumn(
                $columns['status'],
                array(
                    FilterConditionOperator::EQUALS => $main_editor,
                    FilterConditionOperator::DOES_NOT_EQUAL => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_BETWEEN => $main_editor,
                    FilterConditionOperator::IS_NOT_BETWEEN => $main_editor,
                    FilterConditionOperator::CONTAINS => $text_editor,
                    FilterConditionOperator::DOES_NOT_CONTAIN => $text_editor,
                    FilterConditionOperator::BEGINS_WITH => $text_editor,
                    FilterConditionOperator::ENDS_WITH => $text_editor,
                    FilterConditionOperator::IS_LIKE => $text_editor,
                    FilterConditionOperator::IS_NOT_LIKE => $text_editor,
                    FilterConditionOperator::IN => $multi_value_select_editor,
                    FilterConditionOperator::NOT_IN => $multi_value_select_editor,
                    FilterConditionOperator::IS_BLANK => null,
                    FilterConditionOperator::IS_NOT_BLANK => null
                )
            );
            
            $main_editor = new DateTimeEdit('date_edit', false, 'Y-m-d');
            
            $filterBuilder->addColumn(
                $columns['date'],
                array(
                    FilterConditionOperator::EQUALS => $main_editor,
                    FilterConditionOperator::DOES_NOT_EQUAL => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_BETWEEN => $main_editor,
                    FilterConditionOperator::IS_NOT_BETWEEN => $main_editor,
                    FilterConditionOperator::DATE_EQUALS => $main_editor,
                    FilterConditionOperator::DATE_DOES_NOT_EQUAL => $main_editor,
                    FilterConditionOperator::TODAY => null,
                    FilterConditionOperator::IS_BLANK => null,
                    FilterConditionOperator::IS_NOT_BLANK => null
                )
            );
            
            $main_editor = new AutocompleteComboBox('order_id_edit', $this->CreateLinkBuilder());
            $main_editor->setAllowClear(true);
            $main_editor->setMinimumInputLength(0);
            $main_editor->SetAllowNullValue(false);
            $main_editor->SetHandlerName('filter_builder_order_id_order_id_fullname_search');
            
            $multi_value_select_editor = new RemoteMultiValueSelect('order_id', $this->CreateLinkBuilder());
            $multi_value_select_editor->SetHandlerName('filter_builder_order_id_order_id_fullname_search');
            
            $filterBuilder->addColumn(
                $columns['order_id'],
                array(
                    FilterConditionOperator::IN => $multi_value_select_editor,
                    FilterConditionOperator::NOT_IN => $multi_value_select_editor,
                    FilterConditionOperator::IS_BLANK => null,
                    FilterConditionOperator::IS_NOT_BLANK => null
                )
            );
        }
    
        protected function AddOperationsColumns(Grid $grid)
        {
            $actions = $grid->getActions();
            $actions->setCaption($this->GetLocalizerCaptions()->GetMessageString('Actions'));
            $actions->setPosition(ActionList::POSITION_LEFT);
            
            if ($this->GetSecurityInfo()->HasViewGrant())
            {
                $operation = new LinkOperation($this->GetLocalizerCaptions()->GetMessageString('View'), OPERATION_VIEW, $this->dataset, $grid);
                $operation->setUseImage(true);
                $actions->addOperation($operation);
            }
            
            if ($this->GetSecurityInfo()->HasEditGrant())
            {
                $operation = new LinkOperation($this->GetLocalizerCaptions()->GetMessageString('Edit'), OPERATION_EDIT, $this->dataset, $grid);
                $operation->setUseImage(true);
                $actions->addOperation($operation);
                $operation->OnShow->AddListener('ShowEditButtonHandler', $this);
            }
            
            if ($this->GetSecurityInfo()->HasDeleteGrant())
            {
                $operation = new LinkOperation($this->GetLocalizerCaptions()->GetMessageString('Delete'), OPERATION_DELETE, $this->dataset, $grid);
                $operation->setUseImage(true);
                $actions->addOperation($operation);
                $operation->OnShow->AddListener('ShowDeleteButtonHandler', $this);
                $operation->SetAdditionalAttribute('data-modal-operation', 'delete');
                $operation->SetAdditionalAttribute('data-delete-handler-name', $this->GetModalGridDeleteHandler());
            }
            
            if ($this->GetSecurityInfo()->HasAddGrant())
            {
                $operation = new LinkOperation($this->GetLocalizerCaptions()->GetMessageString('Copy'), OPERATION_COPY, $this->dataset, $grid);
                $operation->setUseImage(true);
                $actions->addOperation($operation);
            }
        }
    
        protected function AddFieldColumns(Grid $grid, $withDetails = true)
        {
            //
            // View column for order_history_id field
            //
            $column = new NumberViewColumn('order_history_id', 'order_history_id', 'Id', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(' ');
            $column->setDecimalSeparator('');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for F2 field
            //
            $column = new TextViewColumn('status', 'status_F2', 'Status', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('order_historyGrid_status_F2_handler_list');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for date field
            //
            $column = new DateTimeViewColumn('date', 'date', 'Date', $this->dataset);
            $column->SetDateTimeFormat('Y-m-d');
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for order_id_fullname field
            //
            $column = new TextViewColumn('order_id', 'order_id_order_id_fullname', 'Order', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
        }
    
        protected function AddSingleRecordViewColumns(Grid $grid)
        {
            //
            // View column for order_history_id field
            //
            $column = new NumberViewColumn('order_history_id', 'order_history_id', 'Id', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(' ');
            $column->setDecimalSeparator('');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for F2 field
            //
            $column = new TextViewColumn('status', 'status_F2', 'Status', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('order_historyGrid_status_F2_handler_view');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for date field
            //
            $column = new DateTimeViewColumn('date', 'date', 'Date', $this->dataset);
            $column->SetDateTimeFormat('Y-m-d');
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for order_id_fullname field
            //
            $column = new TextViewColumn('order_id', 'order_id_order_id_fullname', 'Order', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
        }
    
        protected function AddEditColumns(Grid $grid)
        {
            //
            // Edit column for status field
            //
            $editor = new ComboBox('status_edit', $this->GetLocalizerCaptions()->GetMessageString('PleaseSelect'));
            $editor->addChoice('0', 'on process');
            $editor->addChoice('1', 'on delivery');
            $editor->addChoice('2', 'completed');
            $selectQuery = 'SELECT 0 AS F1, \'on process\' AS F2
            UNION ALL SELECT 1, \'on delivery\'
            UNION ALL SELECT 2, \'completed\'';
            $insertQuery = array();
            $updateQuery = array();
            $deleteQuery = array();
            $lookupDataset = new QueryDataset(
              MySqlIConnectionFactory::getInstance(), 
              GetConnectionOptions(),
              $selectQuery, $insertQuery, $updateQuery, $deleteQuery, 'Query-selcetbox-order-status');
            $field = new StringField('F1');
            $lookupDataset->AddField($field, true);
            $field = new StringField('F2');
            $lookupDataset->AddField($field, false);
            $lookupDataset->setOrderByField('F2', GetOrderTypeAsSQL(otAscending));
            $editColumn = new LookUpEditColumn(
                'Status', 
                'status', 
                $editor, 
                $this->dataset, 'F1', 'F2', $lookupDataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for date field
            //
            $editor = new DateTimeEdit('date_edit', false, 'Y-m-d');
            $editColumn = new CustomEditColumn('Date', 'date', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for order_id field
            //
            $editor = new AutocompleteComboBox('order_id_edit', $this->CreateLinkBuilder());
            $editor->setAllowClear(true);
            $editor->setMinimumInputLength(0);
            $selectQuery = 'SELECT O.*, 
            CONCAT(\'#\',O.order_id, \' (\', U.firstname, \' \', U.lastname, \')\') as order_id_fullname,  
            CONCAT(U.firstname, \' \', U.lastname) as user_fullname, 
            U.firstname AS user_firstname, U.lastname AS user_lastname 
            FROM `order` AS O  
            INNER JOIN `user` AS U ON O.user_id = U.user_id';
            $insertQuery = array();
            $updateQuery = array();
            $deleteQuery = array();
            $lookupDataset = new QueryDataset(
              MySqlIConnectionFactory::getInstance(), 
              GetConnectionOptions(),
              $selectQuery, $insertQuery, $updateQuery, $deleteQuery, 'Query_orders');
            $field = new StringField('order_id');
            $lookupDataset->AddField($field, true);
            $field = new IntegerField('Order_TotalAmount');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('current_status');
            $lookupDataset->AddField($field, false);
            $field = new StringField('shipping_address');
            $lookupDataset->AddField($field, false);
            $field = new StringField('billing_address');
            $lookupDataset->AddField($field, false);
            $field = new StringField('user_id');
            $lookupDataset->AddField($field, false);
            $field = new BlobField('order_id_fullname');
            $lookupDataset->AddField($field, false);
            $field = new StringField('user_fullname');
            $lookupDataset->AddField($field, false);
            $field = new StringField('user_firstname');
            $lookupDataset->AddField($field, false);
            $field = new StringField('user_lastname');
            $lookupDataset->AddField($field, false);
            $lookupDataset->setOrderByField('order_id_fullname', GetOrderTypeAsSQL(otAscending));
            $editColumn = new DynamicLookupEditColumn('Order', 'order_id', 'order_id_order_id_fullname', 'edit_order_id_order_id_fullname_search', $editor, $this->dataset, $lookupDataset, 'order_id', 'order_id_fullname', '');
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
        }
    
        protected function AddInsertColumns(Grid $grid)
        {
            //
            // Edit column for status field
            //
            $editor = new ComboBox('status_edit', $this->GetLocalizerCaptions()->GetMessageString('PleaseSelect'));
            $editor->addChoice('0', 'on process');
            $editor->addChoice('1', 'on delivery');
            $editor->addChoice('2', 'completed');
            $selectQuery = 'SELECT 0 AS F1, \'on process\' AS F2
            UNION ALL SELECT 1, \'on delivery\'
            UNION ALL SELECT 2, \'completed\'';
            $insertQuery = array();
            $updateQuery = array();
            $deleteQuery = array();
            $lookupDataset = new QueryDataset(
              MySqlIConnectionFactory::getInstance(), 
              GetConnectionOptions(),
              $selectQuery, $insertQuery, $updateQuery, $deleteQuery, 'Query-selcetbox-order-status');
            $field = new StringField('F1');
            $lookupDataset->AddField($field, true);
            $field = new StringField('F2');
            $lookupDataset->AddField($field, false);
            $lookupDataset->setOrderByField('F2', GetOrderTypeAsSQL(otAscending));
            $editColumn = new LookUpEditColumn(
                'Status', 
                'status', 
                $editor, 
                $this->dataset, 'F1', 'F2', $lookupDataset);
            $editColumn->SetInsertDefaultValue('0');
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for date field
            //
            $editor = new DateTimeEdit('date_edit', false, 'Y-m-d');
            $editColumn = new CustomEditColumn('Date', 'date', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for order_id field
            //
            $editor = new AutocompleteComboBox('order_id_edit', $this->CreateLinkBuilder());
            $editor->setAllowClear(true);
            $editor->setMinimumInputLength(0);
            $selectQuery = 'SELECT O.*, 
            CONCAT(\'#\',O.order_id, \' (\', U.firstname, \' \', U.lastname, \')\') as order_id_fullname,  
            CONCAT(U.firstname, \' \', U.lastname) as user_fullname, 
            U.firstname AS user_firstname, U.lastname AS user_lastname 
            FROM `order` AS O  
            INNER JOIN `user` AS U ON O.user_id = U.user_id';
            $insertQuery = array();
            $updateQuery = array();
            $deleteQuery = array();
            $lookupDataset = new QueryDataset(
              MySqlIConnectionFactory::getInstance(), 
              GetConnectionOptions(),
              $selectQuery, $insertQuery, $updateQuery, $deleteQuery, 'Query_orders');
            $field = new StringField('order_id');
            $lookupDataset->AddField($field, true);
            $field = new IntegerField('Order_TotalAmount');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('current_status');
            $lookupDataset->AddField($field, false);
            $field = new StringField('shipping_address');
            $lookupDataset->AddField($field, false);
            $field = new StringField('billing_address');
            $lookupDataset->AddField($field, false);
            $field = new StringField('user_id');
            $lookupDataset->AddField($field, false);
            $field = new BlobField('order_id_fullname');
            $lookupDataset->AddField($field, false);
            $field = new StringField('user_fullname');
            $lookupDataset->AddField($field, false);
            $field = new StringField('user_firstname');
            $lookupDataset->AddField($field, false);
            $field = new StringField('user_lastname');
            $lookupDataset->AddField($field, false);
            $lookupDataset->setOrderByField('order_id_fullname', GetOrderTypeAsSQL(otAscending));
            $editColumn = new DynamicLookupEditColumn('Order', 'order_id', 'order_id_order_id_fullname', 'insert_order_id_order_id_fullname_search', $editor, $this->dataset, $lookupDataset, 'order_id', 'order_id_fullname', '');
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            $grid->SetShowAddButton(true && $this->GetSecurityInfo()->HasAddGrant());
        }
    
        protected function AddPrintColumns(Grid $grid)
        {
            //
            // View column for order_history_id field
            //
            $column = new NumberViewColumn('order_history_id', 'order_history_id', 'Id', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(' ');
            $column->setDecimalSeparator('');
            $grid->AddPrintColumn($column);
            
            //
            // View column for F2 field
            //
            $column = new TextViewColumn('status', 'status_F2', 'Status', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('order_historyGrid_status_F2_handler_print');
            $grid->AddPrintColumn($column);
            
            //
            // View column for date field
            //
            $column = new DateTimeViewColumn('date', 'date', 'Date', $this->dataset);
            $column->SetDateTimeFormat('Y-m-d');
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for order_id_fullname field
            //
            $column = new TextViewColumn('order_id', 'order_id_order_id_fullname', 'Order', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
        }
    
        protected function AddExportColumns(Grid $grid)
        {
            //
            // View column for order_history_id field
            //
            $column = new NumberViewColumn('order_history_id', 'order_history_id', 'Id', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(' ');
            $column->setDecimalSeparator('');
            $grid->AddExportColumn($column);
            
            //
            // View column for F2 field
            //
            $column = new TextViewColumn('status', 'status_F2', 'Status', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('order_historyGrid_status_F2_handler_export');
            $grid->AddExportColumn($column);
            
            //
            // View column for date field
            //
            $column = new DateTimeViewColumn('date', 'date', 'Date', $this->dataset);
            $column->SetDateTimeFormat('Y-m-d');
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for order_id_fullname field
            //
            $column = new TextViewColumn('order_id', 'order_id_order_id_fullname', 'Order', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
        }
    
        private function AddCompareColumns(Grid $grid)
        {
            //
            // View column for F2 field
            //
            $column = new TextViewColumn('status', 'status_F2', 'Status', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('order_historyGrid_status_F2_handler_compare');
            $grid->AddCompareColumn($column);
            
            //
            // View column for date field
            //
            $column = new DateTimeViewColumn('date', 'date', 'Date', $this->dataset);
            $column->SetDateTimeFormat('Y-m-d');
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for order_id_fullname field
            //
            $column = new TextViewColumn('order_id', 'order_id_order_id_fullname', 'Order', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
        }
    
        private function AddCompareHeaderColumns(Grid $grid)
        {
    
        }
    
        public function GetPageDirection()
        {
            return null;
        }
    
        public function isFilterConditionRequired()
        {
            return false;
        }
    
        protected function ApplyCommonColumnEditProperties(CustomEditColumn $column)
        {
            $column->SetDisplaySetToNullCheckBox(false);
            $column->SetDisplaySetToDefaultCheckBox(false);
    		$column->SetVariableContainer($this->GetColumnVariableContainer());
        }
    
        function GetCustomClientScript()
        {
            return ;
        }
        
        function GetOnPageLoadedClientScript()
        {
            return ;
        }
        protected function GetEnableModalGridDelete() { return true; }
    
        protected function CreateGrid()
        {
            $result = new Grid($this, $this->dataset);
            if ($this->GetSecurityInfo()->HasDeleteGrant())
               $result->SetAllowDeleteSelected(true);
            else
               $result->SetAllowDeleteSelected(false);   
            
            ApplyCommonPageSettings($this, $result);
            
            $result->SetUseImagesForActions(true);
            $result->SetUseFixedHeader(false);
            $result->SetShowLineNumbers(false);
            $result->SetShowKeyColumnsImagesInHeader(false);
            $result->SetViewMode(ViewMode::TABLE);
            $result->setEnableRuntimeCustomization(true);
            $result->setAllowCompare(true);
            $this->AddCompareHeaderColumns($result);
            $this->AddCompareColumns($result);
            $result->setTableBordered(false);
            $result->setTableCondensed(false);
            
            $result->SetHighlightRowAtHover(false);
            $result->SetWidth('');
            $this->AddOperationsColumns($result);
            $this->AddFieldColumns($result);
            $this->AddSingleRecordViewColumns($result);
            $this->AddEditColumns($result);
            $this->AddInsertColumns($result);
            $this->AddPrintColumns($result);
            $this->AddExportColumns($result);
    
    
            $this->SetShowPageList(true);
            $this->SetShowTopPageNavigator(true);
            $this->SetShowBottomPageNavigator(true);
            $this->setPrintListAvailable(true);
            $this->setPrintListRecordAvailable(false);
            $this->setPrintOneRecordAvailable(true);
            $this->setExportListAvailable(array('excel','word','xml','csv','pdf'));
            $this->setExportListRecordAvailable(array());
            $this->setExportOneRecordAvailable(array('excel','word','xml','csv','pdf'));
            $this->setDescription('Orders history');
            $this->setDetailedDescription('Orders history');
    
            return $result;
        }
     
        protected function setClientSideEvents(Grid $grid) {
    
        }
    
        protected function doRegisterHandlers() {
            //
            // View column for F2 field
            //
            $column = new TextViewColumn('status', 'status_F2', 'Status', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'order_historyGrid_status_F2_handler_list', $column);
            GetApplication()->RegisterHTTPHandler($handler);//
            // View column for F2 field
            //
            $column = new TextViewColumn('status', 'status_F2', 'Status', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'order_historyGrid_status_F2_handler_print', $column);
            GetApplication()->RegisterHTTPHandler($handler);//
            // View column for F2 field
            //
            $column = new TextViewColumn('status', 'status_F2', 'Status', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'order_historyGrid_status_F2_handler_compare', $column);
            GetApplication()->RegisterHTTPHandler($handler);$selectQuery = 'SELECT O.*, 
            CONCAT(\'#\',O.order_id, \' (\', U.firstname, \' \', U.lastname, \')\') as order_id_fullname,  
            CONCAT(U.firstname, \' \', U.lastname) as user_fullname, 
            U.firstname AS user_firstname, U.lastname AS user_lastname 
            FROM `order` AS O  
            INNER JOIN `user` AS U ON O.user_id = U.user_id';
            $insertQuery = array();
            $updateQuery = array();
            $deleteQuery = array();
            $lookupDataset = new QueryDataset(
              MySqlIConnectionFactory::getInstance(), 
              GetConnectionOptions(),
              $selectQuery, $insertQuery, $updateQuery, $deleteQuery, 'Query_orders');
            $field = new StringField('order_id');
            $lookupDataset->AddField($field, true);
            $field = new IntegerField('Order_TotalAmount');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('current_status');
            $lookupDataset->AddField($field, false);
            $field = new StringField('shipping_address');
            $lookupDataset->AddField($field, false);
            $field = new StringField('billing_address');
            $lookupDataset->AddField($field, false);
            $field = new StringField('user_id');
            $lookupDataset->AddField($field, false);
            $field = new BlobField('order_id_fullname');
            $lookupDataset->AddField($field, false);
            $field = new StringField('user_fullname');
            $lookupDataset->AddField($field, false);
            $field = new StringField('user_firstname');
            $lookupDataset->AddField($field, false);
            $field = new StringField('user_lastname');
            $lookupDataset->AddField($field, false);
            $lookupDataset->setOrderByField('order_id_fullname', GetOrderTypeAsSQL(otAscending));
            $lookupDataset->AddCustomCondition(EnvVariablesUtils::EvaluateVariableTemplate($this->GetColumnVariableContainer(), ''));
            $handler = new DynamicSearchHandler($lookupDataset, $this, 'insert_order_id_order_id_fullname_search', 'order_id', 'order_id_fullname', null, 20);
            GetApplication()->RegisterHTTPHandler($handler);
            $selectQuery = 'SELECT 0 AS F1, \'on process\' AS F2
            UNION ALL SELECT 1, \'on delivery\'
            UNION ALL SELECT 2, \'completed\'';
            $insertQuery = array();
            $updateQuery = array();
            $deleteQuery = array();
            $lookupDataset = new QueryDataset(
              MySqlIConnectionFactory::getInstance(), 
              GetConnectionOptions(),
              $selectQuery, $insertQuery, $updateQuery, $deleteQuery, 'Query-selcetbox-order-status');
            $field = new StringField('F1');
            $lookupDataset->AddField($field, true);
            $field = new StringField('F2');
            $lookupDataset->AddField($field, false);
            $lookupDataset->setOrderByField('F2', GetOrderTypeAsSQL(otAscending));
            $lookupDataset->AddCustomCondition(EnvVariablesUtils::EvaluateVariableTemplate($this->GetColumnVariableContainer(), ''));
            $handler = new DynamicSearchHandler($lookupDataset, $this, 'filter_builder_status_F2_search', 'F1', 'F2', null, 20);
            GetApplication()->RegisterHTTPHandler($handler);
            
            $selectQuery = 'SELECT O.*, 
            CONCAT(\'#\',O.order_id, \' (\', U.firstname, \' \', U.lastname, \')\') as order_id_fullname,  
            CONCAT(U.firstname, \' \', U.lastname) as user_fullname, 
            U.firstname AS user_firstname, U.lastname AS user_lastname 
            FROM `order` AS O  
            INNER JOIN `user` AS U ON O.user_id = U.user_id';
            $insertQuery = array();
            $updateQuery = array();
            $deleteQuery = array();
            $lookupDataset = new QueryDataset(
              MySqlIConnectionFactory::getInstance(), 
              GetConnectionOptions(),
              $selectQuery, $insertQuery, $updateQuery, $deleteQuery, 'Query_orders');
            $field = new StringField('order_id');
            $lookupDataset->AddField($field, true);
            $field = new IntegerField('Order_TotalAmount');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('current_status');
            $lookupDataset->AddField($field, false);
            $field = new StringField('shipping_address');
            $lookupDataset->AddField($field, false);
            $field = new StringField('billing_address');
            $lookupDataset->AddField($field, false);
            $field = new StringField('user_id');
            $lookupDataset->AddField($field, false);
            $field = new BlobField('order_id_fullname');
            $lookupDataset->AddField($field, false);
            $field = new StringField('user_fullname');
            $lookupDataset->AddField($field, false);
            $field = new StringField('user_firstname');
            $lookupDataset->AddField($field, false);
            $field = new StringField('user_lastname');
            $lookupDataset->AddField($field, false);
            $lookupDataset->setOrderByField('order_id_fullname', GetOrderTypeAsSQL(otAscending));
            $lookupDataset->AddCustomCondition(EnvVariablesUtils::EvaluateVariableTemplate($this->GetColumnVariableContainer(), ''));
            $handler = new DynamicSearchHandler($lookupDataset, $this, 'filter_builder_order_id_order_id_fullname_search', 'order_id', 'order_id_fullname', null, 20);
            GetApplication()->RegisterHTTPHandler($handler);//
            // View column for F2 field
            //
            $column = new TextViewColumn('status', 'status_F2', 'Status', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'order_historyGrid_status_F2_handler_view', $column);
            GetApplication()->RegisterHTTPHandler($handler);$selectQuery = 'SELECT O.*, 
            CONCAT(\'#\',O.order_id, \' (\', U.firstname, \' \', U.lastname, \')\') as order_id_fullname,  
            CONCAT(U.firstname, \' \', U.lastname) as user_fullname, 
            U.firstname AS user_firstname, U.lastname AS user_lastname 
            FROM `order` AS O  
            INNER JOIN `user` AS U ON O.user_id = U.user_id';
            $insertQuery = array();
            $updateQuery = array();
            $deleteQuery = array();
            $lookupDataset = new QueryDataset(
              MySqlIConnectionFactory::getInstance(), 
              GetConnectionOptions(),
              $selectQuery, $insertQuery, $updateQuery, $deleteQuery, 'Query_orders');
            $field = new StringField('order_id');
            $lookupDataset->AddField($field, true);
            $field = new IntegerField('Order_TotalAmount');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('current_status');
            $lookupDataset->AddField($field, false);
            $field = new StringField('shipping_address');
            $lookupDataset->AddField($field, false);
            $field = new StringField('billing_address');
            $lookupDataset->AddField($field, false);
            $field = new StringField('user_id');
            $lookupDataset->AddField($field, false);
            $field = new BlobField('order_id_fullname');
            $lookupDataset->AddField($field, false);
            $field = new StringField('user_fullname');
            $lookupDataset->AddField($field, false);
            $field = new StringField('user_firstname');
            $lookupDataset->AddField($field, false);
            $field = new StringField('user_lastname');
            $lookupDataset->AddField($field, false);
            $lookupDataset->setOrderByField('order_id_fullname', GetOrderTypeAsSQL(otAscending));
            $lookupDataset->AddCustomCondition(EnvVariablesUtils::EvaluateVariableTemplate($this->GetColumnVariableContainer(), ''));
            $handler = new DynamicSearchHandler($lookupDataset, $this, 'edit_order_id_order_id_fullname_search', 'order_id', 'order_id_fullname', null, 20);
            GetApplication()->RegisterHTTPHandler($handler);
        }
       
        protected function doCustomRenderColumn($fieldName, $fieldData, $rowData, &$customText, &$handled)
        { 
    
        }
    
        protected function doCustomRenderPrintColumn($fieldName, $fieldData, $rowData, &$customText, &$handled)
        { 
    
        }
    
        protected function doCustomRenderExportColumn($exportType, $fieldName, $fieldData, $rowData, &$customText, &$handled)
        { 
    
        }
    
        protected function doCustomDrawRow($rowData, &$cellFontColor, &$cellFontSize, &$cellBgColor, &$cellItalicAttr, &$cellBoldAttr)
        {
    
        }
    
        protected function doExtendedCustomDrawRow($rowData, &$rowCellStyles, &$rowStyles, &$rowClasses, &$cellClasses)
        {
    
        }
    
        protected function doCustomRenderTotal($totalValue, $aggregate, $columnName, &$customText, &$handled)
        {
    
        }
    
        protected function doCustomCompareColumn($columnName, $valueA, $valueB, &$result)
        {
    
        }
    
        protected function doBeforeInsertRecord($page, &$rowData, &$cancel, &$message, &$messageDisplayTime, $tableName)
        {
    
        }
    
        protected function doBeforeUpdateRecord($page, &$rowData, &$cancel, &$message, &$messageDisplayTime, $tableName)
        {
    
        }
    
        protected function doBeforeDeleteRecord($page, &$rowData, &$cancel, &$message, &$messageDisplayTime, $tableName)
        {
    
        }
    
        protected function doAfterInsertRecord($page, $rowData, $tableName, &$success, &$message, &$messageDisplayTime)
        {
    
        }
    
        protected function doAfterUpdateRecord($page, $rowData, $tableName, &$success, &$message, &$messageDisplayTime)
        {
    
        }
    
        protected function doAfterDeleteRecord($page, $rowData, $tableName, &$success, &$message, &$messageDisplayTime)
        {
    
        }
    
        protected function doCustomHTMLHeader($page, &$customHtmlHeaderText)
        { 
    
        }
    
        protected function doGetCustomTemplate($type, $part, $mode, &$result, &$params)
        {
    
        }
    
        protected function doGetCustomExportOptions(Page $page, $exportType, $rowData, &$options)
        {
    
        }
    
        protected function doGetCustomUploadFileName($fieldName, $rowData, &$result, &$handled, $originalFileName, $originalFileExtension, $fileSize)
        {
    
        }
    
        protected function doPrepareChart(Chart $chart)
        {
    
        }
    
        protected function doPageLoaded()
        {
    
        }
    
        protected function doGetCustomPagePermissions(Page $page, PermissionSet &$permissions, &$handled)
        {
    
        }
    
        protected function doGetCustomRecordPermissions(Page $page, &$usingCondition, $rowData, &$allowEdit, &$allowDelete, &$mergeWithDefault, &$handled)
        {
    
        }
    
    }

    SetUpUserAuthorization();

    try
    {
        $Page = new order_historyPage("order_history", "order-history.php", GetCurrentUserPermissionSetForDataSource("order_history"), 'UTF-8');
        $Page->SetTitle('Order History');
        $Page->SetMenuLabel('Order history');
        $Page->SetHeader(GetPagesHeader());
        $Page->SetFooter(GetPagesFooter());
        $Page->SetRecordPermission(GetCurrentUserRecordPermissionsForDataSource("order_history"));
        GetApplication()->SetMainPage($Page);
        GetApplication()->Run();
    }
    catch(Exception $e)
    {
        ShowErrorPage($e);
    }
	
