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
    
    
    
    class user_addressPage extends Page
    {
        protected function DoBeforeCreate()
        {
            $this->dataset = new TableDataset(
                MySqlIConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '`user_address`');
            $field = new IntegerField('address_id', null, null, true);
            $field->SetIsNotNull(true);
            $this->dataset->AddField($field, true);
            $field = new StringField('streetnumber');
            $field->SetIsNotNull(true);
            $this->dataset->AddField($field, false);
            $field = new StringField('streetname');
            $field->SetIsNotNull(true);
            $this->dataset->AddField($field, false);
            $field = new StringField('apart_num');
            $field->SetIsNotNull(true);
            $this->dataset->AddField($field, false);
            $field = new StringField('city');
            $field->SetIsNotNull(true);
            $this->dataset->AddField($field, false);
            $field = new StringField('state');
            $field->SetIsNotNull(true);
            $this->dataset->AddField($field, false);
            $field = new StringField('zip');
            $field->SetIsNotNull(true);
            $this->dataset->AddField($field, false);
            $field = new IntegerField('user_id');
            $field->SetIsNotNull(true);
            $this->dataset->AddField($field, false);
            $this->dataset->AddLookupField('user_id', '(SELECT U.*, 
            CONCAT(\'#\',U.user_id, \' (\', U.firstname, \' \', U.lastname, \')\') as user_id_fullname,
            CONCAT(U.firstname, \' \', U.lastname) as user_fullname  
            FROM `user` AS U)', new StringField('user_id'), new StringField('user_fullname', 'user_id_user_fullname', 'user_id_user_fullname_Query_users'), 'user_id_user_fullname_Query_users');
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
                new FilterColumn($this->dataset, 'address_id', 'address_id', 'Address Id'),
                new FilterColumn($this->dataset, 'streetnumber', 'streetnumber', 'Streetnumber'),
                new FilterColumn($this->dataset, 'streetname', 'streetname', 'Streetname'),
                new FilterColumn($this->dataset, 'apart_num', 'apart_num', 'Apart Num'),
                new FilterColumn($this->dataset, 'city', 'city', 'City'),
                new FilterColumn($this->dataset, 'state', 'state', 'State'),
                new FilterColumn($this->dataset, 'zip', 'zip', 'Zip'),
                new FilterColumn($this->dataset, 'user_id', 'user_id_user_fullname', 'User Id')
            );
        }
    
        protected function setupQuickFilter(QuickFilter $quickFilter, FixedKeysArray $columns)
        {
            $quickFilter
                ->addColumn($columns['address_id'])
                ->addColumn($columns['streetnumber'])
                ->addColumn($columns['streetname'])
                ->addColumn($columns['apart_num'])
                ->addColumn($columns['city'])
                ->addColumn($columns['state'])
                ->addColumn($columns['zip'])
                ->addColumn($columns['user_id']);
        }
    
        protected function setupColumnFilter(ColumnFilter $columnFilter)
        {
            $columnFilter
                ->setOptionsFor('user_id');
        }
    
        protected function setupFilterBuilder(FilterBuilder $filterBuilder, FixedKeysArray $columns)
        {
            $main_editor = new TextEdit('address_id_edit');
            
            $filterBuilder->addColumn(
                $columns['address_id'],
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
            
            $main_editor = new TextEdit('streetnumber_edit');
            
            $filterBuilder->addColumn(
                $columns['streetnumber'],
                array(
                    FilterConditionOperator::EQUALS => $main_editor,
                    FilterConditionOperator::DOES_NOT_EQUAL => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_BETWEEN => $main_editor,
                    FilterConditionOperator::IS_NOT_BETWEEN => $main_editor,
                    FilterConditionOperator::CONTAINS => $main_editor,
                    FilterConditionOperator::DOES_NOT_CONTAIN => $main_editor,
                    FilterConditionOperator::BEGINS_WITH => $main_editor,
                    FilterConditionOperator::ENDS_WITH => $main_editor,
                    FilterConditionOperator::IS_LIKE => $main_editor,
                    FilterConditionOperator::IS_NOT_LIKE => $main_editor,
                    FilterConditionOperator::IS_BLANK => null,
                    FilterConditionOperator::IS_NOT_BLANK => null
                )
            );
            
            $main_editor = new TextEdit('streetname_edit');
            
            $filterBuilder->addColumn(
                $columns['streetname'],
                array(
                    FilterConditionOperator::EQUALS => $main_editor,
                    FilterConditionOperator::DOES_NOT_EQUAL => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_BETWEEN => $main_editor,
                    FilterConditionOperator::IS_NOT_BETWEEN => $main_editor,
                    FilterConditionOperator::CONTAINS => $main_editor,
                    FilterConditionOperator::DOES_NOT_CONTAIN => $main_editor,
                    FilterConditionOperator::BEGINS_WITH => $main_editor,
                    FilterConditionOperator::ENDS_WITH => $main_editor,
                    FilterConditionOperator::IS_LIKE => $main_editor,
                    FilterConditionOperator::IS_NOT_LIKE => $main_editor,
                    FilterConditionOperator::IS_BLANK => null,
                    FilterConditionOperator::IS_NOT_BLANK => null
                )
            );
            
            $main_editor = new TextEdit('apart_num_edit');
            
            $filterBuilder->addColumn(
                $columns['apart_num'],
                array(
                    FilterConditionOperator::EQUALS => $main_editor,
                    FilterConditionOperator::DOES_NOT_EQUAL => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_BETWEEN => $main_editor,
                    FilterConditionOperator::IS_NOT_BETWEEN => $main_editor,
                    FilterConditionOperator::CONTAINS => $main_editor,
                    FilterConditionOperator::DOES_NOT_CONTAIN => $main_editor,
                    FilterConditionOperator::BEGINS_WITH => $main_editor,
                    FilterConditionOperator::ENDS_WITH => $main_editor,
                    FilterConditionOperator::IS_LIKE => $main_editor,
                    FilterConditionOperator::IS_NOT_LIKE => $main_editor,
                    FilterConditionOperator::IS_BLANK => null,
                    FilterConditionOperator::IS_NOT_BLANK => null
                )
            );
            
            $main_editor = new TextEdit('city_edit');
            
            $filterBuilder->addColumn(
                $columns['city'],
                array(
                    FilterConditionOperator::EQUALS => $main_editor,
                    FilterConditionOperator::DOES_NOT_EQUAL => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_BETWEEN => $main_editor,
                    FilterConditionOperator::IS_NOT_BETWEEN => $main_editor,
                    FilterConditionOperator::CONTAINS => $main_editor,
                    FilterConditionOperator::DOES_NOT_CONTAIN => $main_editor,
                    FilterConditionOperator::BEGINS_WITH => $main_editor,
                    FilterConditionOperator::ENDS_WITH => $main_editor,
                    FilterConditionOperator::IS_LIKE => $main_editor,
                    FilterConditionOperator::IS_NOT_LIKE => $main_editor,
                    FilterConditionOperator::IS_BLANK => null,
                    FilterConditionOperator::IS_NOT_BLANK => null
                )
            );
            
            $main_editor = new TextEdit('state_edit');
            
            $filterBuilder->addColumn(
                $columns['state'],
                array(
                    FilterConditionOperator::EQUALS => $main_editor,
                    FilterConditionOperator::DOES_NOT_EQUAL => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_BETWEEN => $main_editor,
                    FilterConditionOperator::IS_NOT_BETWEEN => $main_editor,
                    FilterConditionOperator::CONTAINS => $main_editor,
                    FilterConditionOperator::DOES_NOT_CONTAIN => $main_editor,
                    FilterConditionOperator::BEGINS_WITH => $main_editor,
                    FilterConditionOperator::ENDS_WITH => $main_editor,
                    FilterConditionOperator::IS_LIKE => $main_editor,
                    FilterConditionOperator::IS_NOT_LIKE => $main_editor,
                    FilterConditionOperator::IS_BLANK => null,
                    FilterConditionOperator::IS_NOT_BLANK => null
                )
            );
            
            $main_editor = new TextEdit('zip_edit');
            
            $filterBuilder->addColumn(
                $columns['zip'],
                array(
                    FilterConditionOperator::EQUALS => $main_editor,
                    FilterConditionOperator::DOES_NOT_EQUAL => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_BETWEEN => $main_editor,
                    FilterConditionOperator::IS_NOT_BETWEEN => $main_editor,
                    FilterConditionOperator::CONTAINS => $main_editor,
                    FilterConditionOperator::DOES_NOT_CONTAIN => $main_editor,
                    FilterConditionOperator::BEGINS_WITH => $main_editor,
                    FilterConditionOperator::ENDS_WITH => $main_editor,
                    FilterConditionOperator::IS_LIKE => $main_editor,
                    FilterConditionOperator::IS_NOT_LIKE => $main_editor,
                    FilterConditionOperator::IS_BLANK => null,
                    FilterConditionOperator::IS_NOT_BLANK => null
                )
            );
            
            $main_editor = new AutocompleteComboBox('user_id_edit', $this->CreateLinkBuilder());
            $main_editor->setAllowClear(true);
            $main_editor->setMinimumInputLength(0);
            $main_editor->SetAllowNullValue(false);
            $main_editor->SetHandlerName('filter_builder_user_id_user_fullname_search');
            
            $multi_value_select_editor = new RemoteMultiValueSelect('user_id', $this->CreateLinkBuilder());
            $multi_value_select_editor->SetHandlerName('filter_builder_user_id_user_fullname_search');
            
            $text_editor = new TextEdit('user_id');
            
            $filterBuilder->addColumn(
                $columns['user_id'],
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
            // View column for streetnumber field
            //
            $column = new TextViewColumn('streetnumber', 'streetnumber', 'Streetnumber', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('user_addressGrid_streetnumber_handler_list');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for streetname field
            //
            $column = new TextViewColumn('streetname', 'streetname', 'Streetname', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('user_addressGrid_streetname_handler_list');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for apart_num field
            //
            $column = new TextViewColumn('apart_num', 'apart_num', 'Apart Num', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('user_addressGrid_apart_num_handler_list');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for city field
            //
            $column = new TextViewColumn('city', 'city', 'City', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('user_addressGrid_city_handler_list');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for state field
            //
            $column = new TextViewColumn('state', 'state', 'State', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('user_addressGrid_state_handler_list');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for zip field
            //
            $column = new TextViewColumn('zip', 'zip', 'Zip', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('user_addressGrid_zip_handler_list');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for user_fullname field
            //
            $column = new TextViewColumn('user_id', 'user_id_user_fullname', 'User Id', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('user_addressGrid_user_id_user_fullname_handler_list');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
        }
    
        protected function AddSingleRecordViewColumns(Grid $grid)
        {
            //
            // View column for address_id field
            //
            $column = new NumberViewColumn('address_id', 'address_id', 'Address Id', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(' ');
            $column->setDecimalSeparator('');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for streetnumber field
            //
            $column = new TextViewColumn('streetnumber', 'streetnumber', 'Streetnumber', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('user_addressGrid_streetnumber_handler_view');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for streetname field
            //
            $column = new TextViewColumn('streetname', 'streetname', 'Streetname', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('user_addressGrid_streetname_handler_view');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for apart_num field
            //
            $column = new TextViewColumn('apart_num', 'apart_num', 'Apart Num', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('user_addressGrid_apart_num_handler_view');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for city field
            //
            $column = new TextViewColumn('city', 'city', 'City', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('user_addressGrid_city_handler_view');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for state field
            //
            $column = new TextViewColumn('state', 'state', 'State', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('user_addressGrid_state_handler_view');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for zip field
            //
            $column = new TextViewColumn('zip', 'zip', 'Zip', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('user_addressGrid_zip_handler_view');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for user_fullname field
            //
            $column = new TextViewColumn('user_id', 'user_id_user_fullname', 'User Id', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('user_addressGrid_user_id_user_fullname_handler_view');
            $grid->AddSingleRecordViewColumn($column);
        }
    
        protected function AddEditColumns(Grid $grid)
        {
    
        }
    
        protected function AddInsertColumns(Grid $grid)
        {
    
            $grid->SetShowAddButton(true && $this->GetSecurityInfo()->HasAddGrant());
        }
    
        protected function AddPrintColumns(Grid $grid)
        {
            //
            // View column for address_id field
            //
            $column = new NumberViewColumn('address_id', 'address_id', 'Address Id', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(' ');
            $column->setDecimalSeparator('');
            $grid->AddPrintColumn($column);
            
            //
            // View column for streetnumber field
            //
            $column = new TextViewColumn('streetnumber', 'streetnumber', 'Streetnumber', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('user_addressGrid_streetnumber_handler_print');
            $grid->AddPrintColumn($column);
            
            //
            // View column for streetname field
            //
            $column = new TextViewColumn('streetname', 'streetname', 'Streetname', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('user_addressGrid_streetname_handler_print');
            $grid->AddPrintColumn($column);
            
            //
            // View column for apart_num field
            //
            $column = new TextViewColumn('apart_num', 'apart_num', 'Apart Num', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('user_addressGrid_apart_num_handler_print');
            $grid->AddPrintColumn($column);
            
            //
            // View column for city field
            //
            $column = new TextViewColumn('city', 'city', 'City', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('user_addressGrid_city_handler_print');
            $grid->AddPrintColumn($column);
            
            //
            // View column for state field
            //
            $column = new TextViewColumn('state', 'state', 'State', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('user_addressGrid_state_handler_print');
            $grid->AddPrintColumn($column);
            
            //
            // View column for zip field
            //
            $column = new TextViewColumn('zip', 'zip', 'Zip', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('user_addressGrid_zip_handler_print');
            $grid->AddPrintColumn($column);
            
            //
            // View column for user_fullname field
            //
            $column = new TextViewColumn('user_id', 'user_id_user_fullname', 'User Id', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('user_addressGrid_user_id_user_fullname_handler_print');
            $grid->AddPrintColumn($column);
        }
    
        protected function AddExportColumns(Grid $grid)
        {
            //
            // View column for address_id field
            //
            $column = new NumberViewColumn('address_id', 'address_id', 'Address Id', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(' ');
            $column->setDecimalSeparator('');
            $grid->AddExportColumn($column);
            
            //
            // View column for streetnumber field
            //
            $column = new TextViewColumn('streetnumber', 'streetnumber', 'Streetnumber', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('user_addressGrid_streetnumber_handler_export');
            $grid->AddExportColumn($column);
            
            //
            // View column for streetname field
            //
            $column = new TextViewColumn('streetname', 'streetname', 'Streetname', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('user_addressGrid_streetname_handler_export');
            $grid->AddExportColumn($column);
            
            //
            // View column for apart_num field
            //
            $column = new TextViewColumn('apart_num', 'apart_num', 'Apart Num', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('user_addressGrid_apart_num_handler_export');
            $grid->AddExportColumn($column);
            
            //
            // View column for city field
            //
            $column = new TextViewColumn('city', 'city', 'City', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('user_addressGrid_city_handler_export');
            $grid->AddExportColumn($column);
            
            //
            // View column for state field
            //
            $column = new TextViewColumn('state', 'state', 'State', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('user_addressGrid_state_handler_export');
            $grid->AddExportColumn($column);
            
            //
            // View column for zip field
            //
            $column = new TextViewColumn('zip', 'zip', 'Zip', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('user_addressGrid_zip_handler_export');
            $grid->AddExportColumn($column);
            
            //
            // View column for user_fullname field
            //
            $column = new TextViewColumn('user_id', 'user_id_user_fullname', 'User Id', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('user_addressGrid_user_id_user_fullname_handler_export');
            $grid->AddExportColumn($column);
        }
    
        private function AddCompareColumns(Grid $grid)
        {
            //
            // View column for streetnumber field
            //
            $column = new TextViewColumn('streetnumber', 'streetnumber', 'Streetnumber', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('user_addressGrid_streetnumber_handler_compare');
            $grid->AddCompareColumn($column);
            
            //
            // View column for streetname field
            //
            $column = new TextViewColumn('streetname', 'streetname', 'Streetname', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('user_addressGrid_streetname_handler_compare');
            $grid->AddCompareColumn($column);
            
            //
            // View column for apart_num field
            //
            $column = new TextViewColumn('apart_num', 'apart_num', 'Apart Num', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('user_addressGrid_apart_num_handler_compare');
            $grid->AddCompareColumn($column);
            
            //
            // View column for city field
            //
            $column = new TextViewColumn('city', 'city', 'City', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('user_addressGrid_city_handler_compare');
            $grid->AddCompareColumn($column);
            
            //
            // View column for state field
            //
            $column = new TextViewColumn('state', 'state', 'State', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('user_addressGrid_state_handler_compare');
            $grid->AddCompareColumn($column);
            
            //
            // View column for zip field
            //
            $column = new TextViewColumn('zip', 'zip', 'Zip', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('user_addressGrid_zip_handler_compare');
            $grid->AddCompareColumn($column);
            
            //
            // View column for user_fullname field
            //
            $column = new TextViewColumn('user_id', 'user_id_user_fullname', 'User Id', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('user_addressGrid_user_id_user_fullname_handler_compare');
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
            $this->setDescription('Address of users');
            $this->setDetailedDescription('Address of users');
    
            return $result;
        }
     
        protected function setClientSideEvents(Grid $grid) {
    
        }
    
        protected function doRegisterHandlers() {
            //
            // View column for streetnumber field
            //
            $column = new TextViewColumn('streetnumber', 'streetnumber', 'Streetnumber', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'user_addressGrid_streetnumber_handler_list', $column);
            GetApplication()->RegisterHTTPHandler($handler);//
            // View column for streetname field
            //
            $column = new TextViewColumn('streetname', 'streetname', 'Streetname', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'user_addressGrid_streetname_handler_list', $column);
            GetApplication()->RegisterHTTPHandler($handler);//
            // View column for apart_num field
            //
            $column = new TextViewColumn('apart_num', 'apart_num', 'Apart Num', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'user_addressGrid_apart_num_handler_list', $column);
            GetApplication()->RegisterHTTPHandler($handler);//
            // View column for city field
            //
            $column = new TextViewColumn('city', 'city', 'City', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'user_addressGrid_city_handler_list', $column);
            GetApplication()->RegisterHTTPHandler($handler);//
            // View column for state field
            //
            $column = new TextViewColumn('state', 'state', 'State', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'user_addressGrid_state_handler_list', $column);
            GetApplication()->RegisterHTTPHandler($handler);//
            // View column for zip field
            //
            $column = new TextViewColumn('zip', 'zip', 'Zip', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'user_addressGrid_zip_handler_list', $column);
            GetApplication()->RegisterHTTPHandler($handler);//
            // View column for user_fullname field
            //
            $column = new TextViewColumn('user_id', 'user_id_user_fullname', 'User Id', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'user_addressGrid_user_id_user_fullname_handler_list', $column);
            GetApplication()->RegisterHTTPHandler($handler);//
            // View column for streetnumber field
            //
            $column = new TextViewColumn('streetnumber', 'streetnumber', 'Streetnumber', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'user_addressGrid_streetnumber_handler_print', $column);
            GetApplication()->RegisterHTTPHandler($handler);//
            // View column for streetname field
            //
            $column = new TextViewColumn('streetname', 'streetname', 'Streetname', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'user_addressGrid_streetname_handler_print', $column);
            GetApplication()->RegisterHTTPHandler($handler);//
            // View column for apart_num field
            //
            $column = new TextViewColumn('apart_num', 'apart_num', 'Apart Num', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'user_addressGrid_apart_num_handler_print', $column);
            GetApplication()->RegisterHTTPHandler($handler);//
            // View column for city field
            //
            $column = new TextViewColumn('city', 'city', 'City', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'user_addressGrid_city_handler_print', $column);
            GetApplication()->RegisterHTTPHandler($handler);//
            // View column for state field
            //
            $column = new TextViewColumn('state', 'state', 'State', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'user_addressGrid_state_handler_print', $column);
            GetApplication()->RegisterHTTPHandler($handler);//
            // View column for zip field
            //
            $column = new TextViewColumn('zip', 'zip', 'Zip', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'user_addressGrid_zip_handler_print', $column);
            GetApplication()->RegisterHTTPHandler($handler);//
            // View column for user_fullname field
            //
            $column = new TextViewColumn('user_id', 'user_id_user_fullname', 'User Id', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'user_addressGrid_user_id_user_fullname_handler_print', $column);
            GetApplication()->RegisterHTTPHandler($handler);//
            // View column for streetnumber field
            //
            $column = new TextViewColumn('streetnumber', 'streetnumber', 'Streetnumber', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'user_addressGrid_streetnumber_handler_compare', $column);
            GetApplication()->RegisterHTTPHandler($handler);//
            // View column for streetname field
            //
            $column = new TextViewColumn('streetname', 'streetname', 'Streetname', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'user_addressGrid_streetname_handler_compare', $column);
            GetApplication()->RegisterHTTPHandler($handler);//
            // View column for apart_num field
            //
            $column = new TextViewColumn('apart_num', 'apart_num', 'Apart Num', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'user_addressGrid_apart_num_handler_compare', $column);
            GetApplication()->RegisterHTTPHandler($handler);//
            // View column for city field
            //
            $column = new TextViewColumn('city', 'city', 'City', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'user_addressGrid_city_handler_compare', $column);
            GetApplication()->RegisterHTTPHandler($handler);//
            // View column for state field
            //
            $column = new TextViewColumn('state', 'state', 'State', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'user_addressGrid_state_handler_compare', $column);
            GetApplication()->RegisterHTTPHandler($handler);//
            // View column for zip field
            //
            $column = new TextViewColumn('zip', 'zip', 'Zip', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'user_addressGrid_zip_handler_compare', $column);
            GetApplication()->RegisterHTTPHandler($handler);//
            // View column for user_fullname field
            //
            $column = new TextViewColumn('user_id', 'user_id_user_fullname', 'User Id', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'user_addressGrid_user_id_user_fullname_handler_compare', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            $selectQuery = 'SELECT U.*, 
            CONCAT(\'#\',U.user_id, \' (\', U.firstname, \' \', U.lastname, \')\') as user_id_fullname,
            CONCAT(U.firstname, \' \', U.lastname) as user_fullname  
            FROM `user` AS U';
            $insertQuery = array();
            $updateQuery = array();
            $deleteQuery = array();
            $lookupDataset = new QueryDataset(
              MySqlIConnectionFactory::getInstance(), 
              GetConnectionOptions(),
              $selectQuery, $insertQuery, $updateQuery, $deleteQuery, 'Query_users');
            $field = new StringField('user_id');
            $lookupDataset->AddField($field, true);
            $field = new StringField('firstname');
            $lookupDataset->AddField($field, false);
            $field = new StringField('middleInitial');
            $lookupDataset->AddField($field, false);
            $field = new StringField('lastname');
            $lookupDataset->AddField($field, false);
            $field = new StringField('Phone1');
            $lookupDataset->AddField($field, false);
            $field = new StringField('Phone2');
            $lookupDataset->AddField($field, false);
            $field = new StringField('credit_card_name');
            $lookupDataset->AddField($field, false);
            $field = new StringField('credit_card_number');
            $lookupDataset->AddField($field, false);
            $field = new StringField('credit_card_security_code');
            $lookupDataset->AddField($field, false);
            $field = new DateField('credit_card_expiry_date');
            $lookupDataset->AddField($field, false);
            $field = new StringField('account_id');
            $lookupDataset->AddField($field, false);
            $field = new BlobField('user_id_fullname');
            $lookupDataset->AddField($field, false);
            $field = new StringField('user_fullname');
            $lookupDataset->AddField($field, false);
            $lookupDataset->setOrderByField('user_fullname', GetOrderTypeAsSQL(otAscending));
            $lookupDataset->AddCustomCondition(EnvVariablesUtils::EvaluateVariableTemplate($this->GetColumnVariableContainer(), ''));
            $handler = new DynamicSearchHandler($lookupDataset, $this, 'filter_builder_user_id_user_fullname_search', 'user_id', 'user_fullname', null, 20);
            GetApplication()->RegisterHTTPHandler($handler);//
            // View column for streetnumber field
            //
            $column = new TextViewColumn('streetnumber', 'streetnumber', 'Streetnumber', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'user_addressGrid_streetnumber_handler_view', $column);
            GetApplication()->RegisterHTTPHandler($handler);//
            // View column for streetname field
            //
            $column = new TextViewColumn('streetname', 'streetname', 'Streetname', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'user_addressGrid_streetname_handler_view', $column);
            GetApplication()->RegisterHTTPHandler($handler);//
            // View column for apart_num field
            //
            $column = new TextViewColumn('apart_num', 'apart_num', 'Apart Num', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'user_addressGrid_apart_num_handler_view', $column);
            GetApplication()->RegisterHTTPHandler($handler);//
            // View column for city field
            //
            $column = new TextViewColumn('city', 'city', 'City', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'user_addressGrid_city_handler_view', $column);
            GetApplication()->RegisterHTTPHandler($handler);//
            // View column for state field
            //
            $column = new TextViewColumn('state', 'state', 'State', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'user_addressGrid_state_handler_view', $column);
            GetApplication()->RegisterHTTPHandler($handler);//
            // View column for zip field
            //
            $column = new TextViewColumn('zip', 'zip', 'Zip', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'user_addressGrid_zip_handler_view', $column);
            GetApplication()->RegisterHTTPHandler($handler);//
            // View column for user_fullname field
            //
            $column = new TextViewColumn('user_id', 'user_id_user_fullname', 'User Id', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'user_addressGrid_user_id_user_fullname_handler_view', $column);
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
        $Page = new user_addressPage("user_address", "user-address.php", GetCurrentUserPermissionSetForDataSource("user_address"), 'UTF-8');
        $Page->SetTitle('User Address');
        $Page->SetMenuLabel('User Address');
        $Page->SetHeader(GetPagesHeader());
        $Page->SetFooter(GetPagesFooter());
        $Page->SetRecordPermission(GetCurrentUserRecordPermissionsForDataSource("user_address"));
        GetApplication()->SetMainPage($Page);
        GetApplication()->Run();
    }
    catch(Exception $e)
    {
        ShowErrorPage($e);
    }
	
