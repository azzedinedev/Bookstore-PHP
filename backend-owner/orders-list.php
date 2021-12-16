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
    
    
    
    class order_order_bookPage extends DetailPage
    {
        protected function DoBeforeCreate()
        {
            $this->dataset = new TableDataset(
                MySqlIConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '`order_book`');
            $field = new IntegerField('order_book_id', null, null, true);
            $field->SetIsNotNull(true);
            $this->dataset->AddField($field, true);
            $field = new IntegerField('qty');
            $field->SetIsNotNull(true);
            $this->dataset->AddField($field, false);
            $field = new IntegerField('amounts');
            $this->dataset->AddField($field, false);
            $field = new IntegerField('book_id');
            $field->SetIsNotNull(true);
            $this->dataset->AddField($field, false);
            $field = new IntegerField('order_id');
            $field->SetIsNotNull(true);
            $this->dataset->AddField($field, false);
            $this->dataset->AddLookupField('order_id', '(SELECT O.*, 
            CONCAT(\'#\',O.order_id, \' (\', U.firstname, \' \', U.lastname, \')\') as order_id_fullname,  
            CONCAT(U.firstname, \' \', U.lastname) as user_fullname, 
            U.firstname AS user_firstname, U.lastname AS user_lastname 
            FROM `order` AS O  
            INNER JOIN `user` AS U ON O.user_id = U.user_id)', new StringField('order_id'), new BlobField('order_id_fullname', 'order_id_order_id_fullname', 'order_id_order_id_fullname_Query_orders'), 'order_id_order_id_fullname_Query_orders');
            $this->dataset->AddLookupField('book_id', 'book', new IntegerField('book_id', null, null, true), new StringField('title', 'book_id_title', 'book_id_title_book'), 'book_id_title_book');
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
                new FilterColumn($this->dataset, 'order_book_id', 'order_book_id', 'Id'),
                new FilterColumn($this->dataset, 'order_id', 'order_id_order_id_fullname', 'Order'),
                new FilterColumn($this->dataset, 'book_id', 'book_id_title', 'Book'),
                new FilterColumn($this->dataset, 'qty', 'qty', 'Qty'),
                new FilterColumn($this->dataset, 'amounts', 'amounts', 'Amounts')
            );
        }
    
        protected function setupQuickFilter(QuickFilter $quickFilter, FixedKeysArray $columns)
        {
            $quickFilter
                ->addColumn($columns['order_book_id'])
                ->addColumn($columns['order_id'])
                ->addColumn($columns['book_id'])
                ->addColumn($columns['qty'])
                ->addColumn($columns['amounts']);
        }
    
        protected function setupColumnFilter(ColumnFilter $columnFilter)
        {
            $columnFilter
                ->setOptionsFor('order_id')
                ->setOptionsFor('book_id');
        }
    
        protected function setupFilterBuilder(FilterBuilder $filterBuilder, FixedKeysArray $columns)
        {
            $main_editor = new TextEdit('order_book_id_edit');
            
            $filterBuilder->addColumn(
                $columns['order_book_id'],
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
            
            $main_editor = new AutocompleteComboBox('book_id_edit', $this->CreateLinkBuilder());
            $main_editor->setAllowClear(true);
            $main_editor->setMinimumInputLength(0);
            $main_editor->SetAllowNullValue(false);
            $main_editor->SetHandlerName('filter_builder_book_id_title_search');
            
            $multi_value_select_editor = new RemoteMultiValueSelect('book_id', $this->CreateLinkBuilder());
            $multi_value_select_editor->SetHandlerName('filter_builder_book_id_title_search');
            
            $text_editor = new TextEdit('book_id');
            
            $filterBuilder->addColumn(
                $columns['book_id'],
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
            
            $main_editor = new TextEdit('qty_edit');
            
            $filterBuilder->addColumn(
                $columns['qty'],
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
            
            $main_editor = new TextEdit('amounts_edit');
            
            $filterBuilder->addColumn(
                $columns['amounts'],
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
            // View column for order_book_id field
            //
            $column = new NumberViewColumn('order_book_id', 'order_book_id', 'Id', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(' ');
            $column->setDecimalSeparator('');
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
            
            //
            // View column for title field
            //
            $column = new TextViewColumn('book_id', 'book_id_title', 'Book', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('DetailGridorder.order_book_book_id_title_handler_list');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for qty field
            //
            $column = new NumberViewColumn('qty', 'qty', 'Qty', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(' ');
            $column->setDecimalSeparator('');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for amounts field
            //
            $column = new NumberViewColumn('amounts', 'amounts', 'Amounts', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator(' ');
            $column->setDecimalSeparator(',');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
        }
    
        protected function AddSingleRecordViewColumns(Grid $grid)
        {
            //
            // View column for order_book_id field
            //
            $column = new NumberViewColumn('order_book_id', 'order_book_id', 'Id', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(' ');
            $column->setDecimalSeparator('');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for order_id_fullname field
            //
            $column = new TextViewColumn('order_id', 'order_id_order_id_fullname', 'Order', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for title field
            //
            $column = new TextViewColumn('book_id', 'book_id_title', 'Book', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('DetailGridorder.order_book_book_id_title_handler_view');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for qty field
            //
            $column = new NumberViewColumn('qty', 'qty', 'Qty', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(' ');
            $column->setDecimalSeparator('');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for amounts field
            //
            $column = new NumberViewColumn('amounts', 'amounts', 'Amounts', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator(' ');
            $column->setDecimalSeparator(',');
            $grid->AddSingleRecordViewColumn($column);
        }
    
        protected function AddEditColumns(Grid $grid)
        {
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
            
            //
            // Edit column for book_id field
            //
            $editor = new AutocompleteComboBox('book_id_edit', $this->CreateLinkBuilder());
            $editor->setAllowClear(true);
            $editor->setMinimumInputLength(0);
            $lookupDataset = new TableDataset(
                MySqlIConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '`book`');
            $field = new IntegerField('book_id', null, null, true);
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, true);
            $field = new StringField('title');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('is_activated');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('description');
            $lookupDataset->AddField($field, false);
            $field = new StringField('image_src');
            $lookupDataset->AddField($field, false);
            $field = new StringField('isbn');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('purchase_price');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('sale_price');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('quantity');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('nbOfPages');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('genre_id');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('publisher_id');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $lookupDataset->setOrderByField('title', GetOrderTypeAsSQL(otAscending));
            $editColumn = new DynamicLookupEditColumn('Book', 'book_id', 'book_id_title', 'edit_book_id_title_search', $editor, $this->dataset, $lookupDataset, 'book_id', 'title', '');
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for qty field
            //
            $editor = new TextEdit('qty_edit');
            $editColumn = new CustomEditColumn('Qty', 'qty', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for amounts field
            //
            $editor = new TextEdit('amounts_edit');
            $editColumn = new CustomEditColumn('Amounts', 'amounts', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
        }
    
        protected function AddInsertColumns(Grid $grid)
        {
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
            
            //
            // Edit column for book_id field
            //
            $editor = new AutocompleteComboBox('book_id_edit', $this->CreateLinkBuilder());
            $editor->setAllowClear(true);
            $editor->setMinimumInputLength(0);
            $lookupDataset = new TableDataset(
                MySqlIConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '`book`');
            $field = new IntegerField('book_id', null, null, true);
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, true);
            $field = new StringField('title');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('is_activated');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('description');
            $lookupDataset->AddField($field, false);
            $field = new StringField('image_src');
            $lookupDataset->AddField($field, false);
            $field = new StringField('isbn');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('purchase_price');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('sale_price');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('quantity');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('nbOfPages');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('genre_id');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('publisher_id');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $lookupDataset->setOrderByField('title', GetOrderTypeAsSQL(otAscending));
            $editColumn = new DynamicLookupEditColumn('Book', 'book_id', 'book_id_title', 'insert_book_id_title_search', $editor, $this->dataset, $lookupDataset, 'book_id', 'title', '');
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for qty field
            //
            $editor = new TextEdit('qty_edit');
            $editColumn = new CustomEditColumn('Qty', 'qty', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for amounts field
            //
            $editor = new TextEdit('amounts_edit');
            $editColumn = new CustomEditColumn('Amounts', 'amounts', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            $grid->SetShowAddButton(true && $this->GetSecurityInfo()->HasAddGrant());
        }
    
        protected function AddPrintColumns(Grid $grid)
        {
            //
            // View column for order_book_id field
            //
            $column = new NumberViewColumn('order_book_id', 'order_book_id', 'Id', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(' ');
            $column->setDecimalSeparator('');
            $grid->AddPrintColumn($column);
            
            //
            // View column for order_id_fullname field
            //
            $column = new TextViewColumn('order_id', 'order_id_order_id_fullname', 'Order', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for title field
            //
            $column = new TextViewColumn('book_id', 'book_id_title', 'Book', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('DetailGridorder.order_book_book_id_title_handler_print');
            $grid->AddPrintColumn($column);
            
            //
            // View column for qty field
            //
            $column = new NumberViewColumn('qty', 'qty', 'Qty', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(' ');
            $column->setDecimalSeparator('');
            $grid->AddPrintColumn($column);
            
            //
            // View column for amounts field
            //
            $column = new NumberViewColumn('amounts', 'amounts', 'Amounts', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator(' ');
            $column->setDecimalSeparator(',');
            $grid->AddPrintColumn($column);
        }
    
        protected function AddExportColumns(Grid $grid)
        {
            //
            // View column for order_book_id field
            //
            $column = new NumberViewColumn('order_book_id', 'order_book_id', 'Id', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(' ');
            $column->setDecimalSeparator('');
            $grid->AddExportColumn($column);
            
            //
            // View column for order_id_fullname field
            //
            $column = new TextViewColumn('order_id', 'order_id_order_id_fullname', 'Order', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for title field
            //
            $column = new TextViewColumn('book_id', 'book_id_title', 'Book', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('DetailGridorder.order_book_book_id_title_handler_export');
            $grid->AddExportColumn($column);
            
            //
            // View column for qty field
            //
            $column = new NumberViewColumn('qty', 'qty', 'Qty', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(' ');
            $column->setDecimalSeparator('');
            $grid->AddExportColumn($column);
            
            //
            // View column for amounts field
            //
            $column = new NumberViewColumn('amounts', 'amounts', 'Amounts', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator(' ');
            $column->setDecimalSeparator(',');
            $grid->AddExportColumn($column);
        }
    
        private function AddCompareColumns(Grid $grid)
        {
            //
            // View column for order_id_fullname field
            //
            $column = new TextViewColumn('order_id', 'order_id_order_id_fullname', 'Order', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for title field
            //
            $column = new TextViewColumn('book_id', 'book_id_title', 'Book', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('DetailGridorder.order_book_book_id_title_handler_compare');
            $grid->AddCompareColumn($column);
            
            //
            // View column for qty field
            //
            $column = new NumberViewColumn('qty', 'qty', 'Qty', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(' ');
            $column->setDecimalSeparator('');
            $grid->AddCompareColumn($column);
            
            //
            // View column for amounts field
            //
            $column = new NumberViewColumn('amounts', 'amounts', 'Amounts', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator(' ');
            $column->setDecimalSeparator(',');
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
    
            return $result;
        }
     
        protected function setClientSideEvents(Grid $grid) {
            $grid->SetInsertClientEditorValueChangedScript($this->RenderText('var bookId 	  = 0;
            var salePrice	  = 0;
            var purchasePrice = 0;
            var url_BookJson;
            var var_BOOK = \'book_id\';
            var var_AMOUNT = \'amounts\';
                        
            // Reinitialiser le format du nombre
            function formatNumber(nStr)
            {  
            	return nStr;
            }
            
            if (sender.getFieldName() == var_BOOK)
            {
            
            //AJAX AMOUNT FROM BOOK
            bookId = editors[var_BOOK].getValue();
            
            url_BookJson = \'order_book.php?hname=edit_book_id_title_search&id=\'+bookId;
            
            $.ajax({
            	type: "POST",
            	url: url_BookJson,
            	data: ({ id: bookId }),
            	success: function(data) {
            		salePrice = data[0].fields.sale_price;
            		purchasePrice = data[0].fields.purchase_price;
            		console.log(\'purchasePrice : \'+purchasePrice+\' / salePrice: \'+salePrice);
                            if(salePrice != 0 ){
                           	     editors[var_AMOUNT].setValue(formatNumber(salePrice));
                            }else{
                           	     editors[var_AMOUNT].setValue(formatNumber(purchasePrice));
                            }
            		
            	}
            });
            
            }'));
            
            $grid->SetEditClientEditorValueChangedScript($this->RenderText('var bookId 	  = 0;
            var salePrice	  = 0;
            var purchasePrice = 0;
            var url_BookJson;
            var var_BOOK = \'book_id\';
            var var_AMOUNT = \'amounts\';
                        
            // Reinitialiser le format du nombre
            function formatNumber(nStr)
            {  
            	return nStr;
            }
            
            if (sender.getFieldName() == var_BOOK)
            {
            
            //AJAX AMOUNT FROM BOOK
            bookId = editors[var_BOOK].getValue();
            
            url_BookJson = \'order_book.php?hname=edit_book_id_title_search&id=\'+bookId;
            
            $.ajax({
            	type: "POST",
            	url: url_BookJson,
            	data: ({ id: bookId }),
            	success: function(data) {
            		salePrice = data[0].fields.sale_price;
            		purchasePrice = data[0].fields.purchase_price;
            		console.log(\'purchasePrice : \'+purchasePrice+\' / salePrice: \'+salePrice);
                            if(salePrice != 0 ){
                           	     editors[var_AMOUNT].setValue(formatNumber(salePrice));
                            }else{
                           	     editors[var_AMOUNT].setValue(formatNumber(purchasePrice));
                            }
            		
            	}
            });
            
            }'));
        }
    
        protected function doRegisterHandlers() {
            //
            // View column for title field
            //
            $column = new TextViewColumn('book_id', 'book_id_title', 'Book', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'DetailGridorder.order_book_book_id_title_handler_list', $column);
            GetApplication()->RegisterHTTPHandler($handler);//
            // View column for title field
            //
            $column = new TextViewColumn('book_id', 'book_id_title', 'Book', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'DetailGridorder.order_book_book_id_title_handler_print', $column);
            GetApplication()->RegisterHTTPHandler($handler);//
            // View column for title field
            //
            $column = new TextViewColumn('book_id', 'book_id_title', 'Book', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'DetailGridorder.order_book_book_id_title_handler_compare', $column);
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
            GetApplication()->RegisterHTTPHandler($handler);$lookupDataset = new TableDataset(
                MySqlIConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '`book`');
            $field = new IntegerField('book_id', null, null, true);
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, true);
            $field = new StringField('title');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('is_activated');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('description');
            $lookupDataset->AddField($field, false);
            $field = new StringField('image_src');
            $lookupDataset->AddField($field, false);
            $field = new StringField('isbn');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('purchase_price');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('sale_price');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('quantity');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('nbOfPages');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('genre_id');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('publisher_id');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $lookupDataset->setOrderByField('title', GetOrderTypeAsSQL(otAscending));
            $lookupDataset->AddCustomCondition(EnvVariablesUtils::EvaluateVariableTemplate($this->GetColumnVariableContainer(), ''));
            $handler = new DynamicSearchHandler($lookupDataset, $this, 'insert_book_id_title_search', 'book_id', 'title', null, 20);
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
            GetApplication()->RegisterHTTPHandler($handler);
            
            $lookupDataset = new TableDataset(
                MySqlIConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '`book`');
            $field = new IntegerField('book_id', null, null, true);
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, true);
            $field = new StringField('title');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('is_activated');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('description');
            $lookupDataset->AddField($field, false);
            $field = new StringField('image_src');
            $lookupDataset->AddField($field, false);
            $field = new StringField('isbn');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('purchase_price');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('sale_price');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('quantity');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('nbOfPages');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('genre_id');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('publisher_id');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $lookupDataset->setOrderByField('title', GetOrderTypeAsSQL(otAscending));
            $lookupDataset->AddCustomCondition(EnvVariablesUtils::EvaluateVariableTemplate($this->GetColumnVariableContainer(), ''));
            $handler = new DynamicSearchHandler($lookupDataset, $this, 'filter_builder_book_id_title_search', 'book_id', 'title', null, 20);
            GetApplication()->RegisterHTTPHandler($handler);//
            // View column for title field
            //
            $column = new TextViewColumn('book_id', 'book_id_title', 'Book', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'DetailGridorder.order_book_book_id_title_handler_view', $column);
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
            GetApplication()->RegisterHTTPHandler($handler);$lookupDataset = new TableDataset(
                MySqlIConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '`book`');
            $field = new IntegerField('book_id', null, null, true);
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, true);
            $field = new StringField('title');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('is_activated');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('description');
            $lookupDataset->AddField($field, false);
            $field = new StringField('image_src');
            $lookupDataset->AddField($field, false);
            $field = new StringField('isbn');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('purchase_price');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('sale_price');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('quantity');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('nbOfPages');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('genre_id');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('publisher_id');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $lookupDataset->setOrderByField('title', GetOrderTypeAsSQL(otAscending));
            $lookupDataset->AddCustomCondition(EnvVariablesUtils::EvaluateVariableTemplate($this->GetColumnVariableContainer(), ''));
            $handler = new DynamicSearchHandler($lookupDataset, $this, 'edit_book_id_title_search', 'book_id', 'title', null, 20);
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
            //DB TABLES
            $DTBL = 'order_book';
            $GTBL = 'order';
            
            //DB FIELD
            //Articles
            $DFID  = 'order_id';
            $DFQTY  = 'qty';
            $DFAMNT = 'amounts';
            $GFID  = 'order_id';
            $GFTOT  = 'Order_TotalAmount';
            
            //Sum of total Articles
            $sql1 =
                  "SELECT SUM(".$DFQTY." * ".$DFAMNT.") AS TOTAL " .  
                  "FROM `".$DTBL."` " . 
                  "WHERE ".$DFID." = '".$rowData[$DFID]."'";  
                   
            $this->GetConnection()->ExecQueryToArray($sql1, $details);
            
            $TOTAL = (float) $details[0]['TOTAL'];
              
            //UPDATE GLOBAL
            $sql2 =
                  "UPDATE `".$GTBL."` SET "
                  .$GFTOT." = '".$TOTAL."' "
                  ."WHERE ".$GFID." = '".intval($rowData[$DFID])."'";  
            
            $this->GetConnection()->ExecSQL($sql2);
               
            if ($success) {
                $message = "Succes on update of the total amount";
            }  else{
            
                $message = "Fails on update of the total amount";
            }
        }
    
        protected function doAfterUpdateRecord($page, $rowData, $tableName, &$success, &$message, &$messageDisplayTime)
        {
            //DB TABLES
            $DTBL = 'order_book';
            $GTBL = 'order';
            
            //DB FIELD
            //Articles
            $DFID  = 'order_id';
            $DFQTY  = 'qty';
            $DFAMNT = 'amounts';
            $GFID  = 'order_id';
            $GFTOT  = 'Order_TotalAmount';
            
            //Sum of total Articles
            $sql1 =
                  "SELECT SUM(".$DFQTY." * ".$DFAMNT.") AS TOTAL " .  
                  "FROM `".$DTBL."` " . 
                  "WHERE ".$DFID." = '".$rowData[$DFID]."'";  
                   
            $this->GetConnection()->ExecQueryToArray($sql1, $details);
            
            $TOTAL = (float) $details[0]['TOTAL'];
              
            //UPDATE GLOBAL
            $sql2 =
                  "UPDATE `".$GTBL."` SET "
                  .$GFTOT." = '".$TOTAL."' "
                  ."WHERE ".$GFID." = '".intval($rowData[$DFID])."'";  
            
            $this->GetConnection()->ExecSQL($sql2);
               
            if ($success) {
                $message = "Succes on update of the total amount";
            }  else{
            
                $message = "Fails on update of the total amount";
            }
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
    
    
    
    
    // OnBeforePageExecute event handler
    
    
    
    class order_order_historyPage extends DetailPage
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
            $column->SetFullTextWindowHandlerName('DetailGridorder.order_history_status_F2_handler_list');
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
            $column->SetFullTextWindowHandlerName('DetailGridorder.order_history_status_F2_handler_view');
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
            $column->SetFullTextWindowHandlerName('DetailGridorder.order_history_status_F2_handler_print');
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
            $column->SetFullTextWindowHandlerName('DetailGridorder.order_history_status_F2_handler_export');
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
            $column->SetFullTextWindowHandlerName('DetailGridorder.order_history_status_F2_handler_compare');
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
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'DetailGridorder.order_history_status_F2_handler_list', $column);
            GetApplication()->RegisterHTTPHandler($handler);//
            // View column for F2 field
            //
            $column = new TextViewColumn('status', 'status_F2', 'Status', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'DetailGridorder.order_history_status_F2_handler_print', $column);
            GetApplication()->RegisterHTTPHandler($handler);//
            // View column for F2 field
            //
            $column = new TextViewColumn('status', 'status_F2', 'Status', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'DetailGridorder.order_history_status_F2_handler_compare', $column);
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
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'DetailGridorder.order_history_status_F2_handler_view', $column);
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
    
    // OnBeforePageExecute event handler
    
    
    
    class orderPage extends Page
    {
        protected function DoBeforeCreate()
        {
            $this->dataset = new TableDataset(
                MySqlIConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '`order`');
            $field = new IntegerField('order_id', null, null, true);
            $field->SetIsNotNull(true);
            $this->dataset->AddField($field, true);
            $field = new IntegerField('Order_TotalAmount');
            $this->dataset->AddField($field, false);
            $field = new IntegerField('current_status');
            $this->dataset->AddField($field, false);
            $field = new StringField('shipping_address');
            $this->dataset->AddField($field, false);
            $field = new IntegerField('billing_address');
            $field->SetIsNotNull(true);
            $this->dataset->AddField($field, false);
            $field = new IntegerField('user_id');
            $field->SetIsNotNull(true);
            $this->dataset->AddField($field, false);
            $this->dataset->AddLookupField('current_status', '(SELECT 0 AS F1, \'on process\' AS F2
            UNION ALL SELECT 1, \'on delivery\'
            UNION ALL SELECT 2, \'completed\')', new StringField('F1'), new StringField('F2', 'current_status_F2', 'current_status_F2_Query-selcetbox-order-status'), 'current_status_F2_Query-selcetbox-order-status');
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
                new FilterColumn($this->dataset, 'order_id', 'order_id', 'Id'),
                new FilterColumn($this->dataset, 'Order_TotalAmount', 'Order_TotalAmount', 'Total Amount'),
                new FilterColumn($this->dataset, 'current_status', 'current_status_F2', 'Current Status'),
                new FilterColumn($this->dataset, 'shipping_address', 'shipping_address', 'Shipping Address'),
                new FilterColumn($this->dataset, 'billing_address', 'billing_address', 'Billing Address'),
                new FilterColumn($this->dataset, 'user_id', 'user_id_user_fullname', 'User')
            );
        }
    
        protected function setupQuickFilter(QuickFilter $quickFilter, FixedKeysArray $columns)
        {
            $quickFilter
                ->addColumn($columns['order_id'])
                ->addColumn($columns['Order_TotalAmount'])
                ->addColumn($columns['current_status'])
                ->addColumn($columns['shipping_address'])
                ->addColumn($columns['billing_address'])
                ->addColumn($columns['user_id']);
        }
    
        protected function setupColumnFilter(ColumnFilter $columnFilter)
        {
            $columnFilter
                ->setOptionsFor('order_id')
                ->setOptionsFor('current_status')
                ->setOptionsFor('user_id');
        }
    
        protected function setupFilterBuilder(FilterBuilder $filterBuilder, FixedKeysArray $columns)
        {
            $main_editor = new TextEdit('order_id_edit');
            
            $filterBuilder->addColumn(
                $columns['order_id'],
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
            
            $main_editor = new TextEdit('order_totalamount_edit');
            
            $filterBuilder->addColumn(
                $columns['Order_TotalAmount'],
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
            
            $main_editor = new AutocompleteComboBox('current_status_edit', $this->CreateLinkBuilder());
            $main_editor->setAllowClear(true);
            $main_editor->setMinimumInputLength(0);
            $main_editor->SetAllowNullValue(false);
            $main_editor->SetHandlerName('filter_builder_current_status_F2_search');
            
            $multi_value_select_editor = new RemoteMultiValueSelect('current_status', $this->CreateLinkBuilder());
            $multi_value_select_editor->SetHandlerName('filter_builder_current_status_F2_search');
            
            $filterBuilder->addColumn(
                $columns['current_status'],
                array(
                    FilterConditionOperator::EQUALS => $main_editor,
                    FilterConditionOperator::DOES_NOT_EQUAL => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_BETWEEN => $main_editor,
                    FilterConditionOperator::IS_NOT_BETWEEN => $main_editor,
                    FilterConditionOperator::IN => $multi_value_select_editor,
                    FilterConditionOperator::NOT_IN => $multi_value_select_editor,
                    FilterConditionOperator::IS_BLANK => null,
                    FilterConditionOperator::IS_NOT_BLANK => null
                )
            );
            
            $main_editor = new TextEdit('shipping_address_edit');
            
            $filterBuilder->addColumn(
                $columns['shipping_address'],
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
            
            $main_editor = new TextEdit('billing_address_edit');
            
            $filterBuilder->addColumn(
                $columns['billing_address'],
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
            if (GetCurrentUserPermissionSetForDataSource('order.order_book')->HasViewGrant() && $withDetails)
            {
            //
            // View column for order_order_book detail
            //
            $column = new DetailColumn(array('order_id'), 'order.order_book', 'order_order_book_handler', $this->dataset, 'Order details');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $grid->AddViewColumn($column);
            }
            
            if (GetCurrentUserPermissionSetForDataSource('order.order_history')->HasViewGrant() && $withDetails)
            {
            //
            // View column for order_order_history detail
            //
            $column = new DetailColumn(array('order_id'), 'order.order_history', 'order_order_history_handler', $this->dataset, 'Order history');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $grid->AddViewColumn($column);
            }
            
            //
            // View column for order_id field
            //
            $column = new NumberViewColumn('order_id', 'order_id', 'Id', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(' ');
            $column->setDecimalSeparator(',');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for Order_TotalAmount field
            //
            $column = new NumberViewColumn('Order_TotalAmount', 'Order_TotalAmount', 'Total Amount', $this->dataset);
            $column->setNullLabel('0');
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator(' ');
            $column->setDecimalSeparator(',');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for F2 field
            //
            $column = new TextViewColumn('current_status', 'current_status_F2', 'Current Status', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for shipping_address field
            //
            $column = new TextViewColumn('shipping_address', 'shipping_address', 'Shipping Address', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('orderGrid_shipping_address_handler_list');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for billing_address field
            //
            $column = new NumberViewColumn('billing_address', 'billing_address', 'Billing Address', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(' ');
            $column->setDecimalSeparator('');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for user_fullname field
            //
            $column = new TextViewColumn('user_id', 'user_id_user_fullname', 'User', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('orderGrid_user_id_user_fullname_handler_list');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
        }
    
        protected function AddSingleRecordViewColumns(Grid $grid)
        {
            //
            // View column for order_id field
            //
            $column = new NumberViewColumn('order_id', 'order_id', 'Id', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(' ');
            $column->setDecimalSeparator(',');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for Order_TotalAmount field
            //
            $column = new NumberViewColumn('Order_TotalAmount', 'Order_TotalAmount', 'Total Amount', $this->dataset);
            $column->setNullLabel('0');
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator(' ');
            $column->setDecimalSeparator(',');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for F2 field
            //
            $column = new TextViewColumn('current_status', 'current_status_F2', 'Current Status', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for shipping_address field
            //
            $column = new TextViewColumn('shipping_address', 'shipping_address', 'Shipping Address', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('orderGrid_shipping_address_handler_view');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for billing_address field
            //
            $column = new NumberViewColumn('billing_address', 'billing_address', 'Billing Address', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(' ');
            $column->setDecimalSeparator('');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for user_fullname field
            //
            $column = new TextViewColumn('user_id', 'user_id_user_fullname', 'User', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('orderGrid_user_id_user_fullname_handler_view');
            $grid->AddSingleRecordViewColumn($column);
        }
    
        protected function AddEditColumns(Grid $grid)
        {
            //
            // Edit column for current_status field
            //
            $editor = new ComboBox('current_status_edit', $this->GetLocalizerCaptions()->GetMessageString('PleaseSelect'));
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
                'Current Status', 
                'current_status', 
                $editor, 
                $this->dataset, 'F1', 'F2', $lookupDataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
        }
    
        protected function AddInsertColumns(Grid $grid)
        {
    
            $grid->SetShowAddButton(true && $this->GetSecurityInfo()->HasAddGrant());
        }
    
        protected function AddPrintColumns(Grid $grid)
        {
            //
            // View column for order_id field
            //
            $column = new NumberViewColumn('order_id', 'order_id', 'Id', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(' ');
            $column->setDecimalSeparator(',');
            $grid->AddPrintColumn($column);
            
            //
            // View column for Order_TotalAmount field
            //
            $column = new NumberViewColumn('Order_TotalAmount', 'Order_TotalAmount', 'Total Amount', $this->dataset);
            $column->setNullLabel('0');
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator(' ');
            $column->setDecimalSeparator(',');
            $grid->AddPrintColumn($column);
            
            //
            // View column for F2 field
            //
            $column = new TextViewColumn('current_status', 'current_status_F2', 'Current Status', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for shipping_address field
            //
            $column = new TextViewColumn('shipping_address', 'shipping_address', 'Shipping Address', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('orderGrid_shipping_address_handler_print');
            $grid->AddPrintColumn($column);
            
            //
            // View column for billing_address field
            //
            $column = new NumberViewColumn('billing_address', 'billing_address', 'Billing Address', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(' ');
            $column->setDecimalSeparator('');
            $grid->AddPrintColumn($column);
            
            //
            // View column for user_fullname field
            //
            $column = new TextViewColumn('user_id', 'user_id_user_fullname', 'User', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('orderGrid_user_id_user_fullname_handler_print');
            $grid->AddPrintColumn($column);
        }
    
        protected function AddExportColumns(Grid $grid)
        {
            //
            // View column for order_id field
            //
            $column = new NumberViewColumn('order_id', 'order_id', 'Id', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(' ');
            $column->setDecimalSeparator(',');
            $grid->AddExportColumn($column);
            
            //
            // View column for Order_TotalAmount field
            //
            $column = new NumberViewColumn('Order_TotalAmount', 'Order_TotalAmount', 'Total Amount', $this->dataset);
            $column->setNullLabel('0');
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator(' ');
            $column->setDecimalSeparator(',');
            $grid->AddExportColumn($column);
            
            //
            // View column for F2 field
            //
            $column = new TextViewColumn('current_status', 'current_status_F2', 'Current Status', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for shipping_address field
            //
            $column = new TextViewColumn('shipping_address', 'shipping_address', 'Shipping Address', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('orderGrid_shipping_address_handler_export');
            $grid->AddExportColumn($column);
            
            //
            // View column for billing_address field
            //
            $column = new NumberViewColumn('billing_address', 'billing_address', 'Billing Address', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(' ');
            $column->setDecimalSeparator('');
            $grid->AddExportColumn($column);
            
            //
            // View column for user_fullname field
            //
            $column = new TextViewColumn('user_id', 'user_id_user_fullname', 'User', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('orderGrid_user_id_user_fullname_handler_export');
            $grid->AddExportColumn($column);
        }
    
        private function AddCompareColumns(Grid $grid)
        {
            //
            // View column for Order_TotalAmount field
            //
            $column = new NumberViewColumn('Order_TotalAmount', 'Order_TotalAmount', 'Total Amount', $this->dataset);
            $column->setNullLabel('0');
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator(' ');
            $column->setDecimalSeparator(',');
            $grid->AddCompareColumn($column);
            
            //
            // View column for F2 field
            //
            $column = new TextViewColumn('current_status', 'current_status_F2', 'Current Status', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for shipping_address field
            //
            $column = new TextViewColumn('shipping_address', 'shipping_address', 'Shipping Address', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('orderGrid_shipping_address_handler_compare');
            $grid->AddCompareColumn($column);
            
            //
            // View column for billing_address field
            //
            $column = new NumberViewColumn('billing_address', 'billing_address', 'Billing Address', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(' ');
            $column->setDecimalSeparator('');
            $grid->AddCompareColumn($column);
            
            //
            // View column for user_fullname field
            //
            $column = new TextViewColumn('user_id', 'user_id_user_fullname', 'User', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('orderGrid_user_id_user_fullname_handler_compare');
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
    
        function CreateMasterDetailRecordGrid()
        {
            $result = new Grid($this, $this->dataset);
            
            $this->AddFieldColumns($result, false);
            $this->AddPrintColumns($result);
            
            $result->SetAllowDeleteSelected(false);
            $result->SetShowUpdateLink(false);
            $result->SetShowKeyColumnsImagesInHeader(false);
            $result->SetViewMode(ViewMode::TABLE);
            $result->setEnableRuntimeCustomization(false);
            $result->setTableBordered(false);
            $result->setTableCondensed(false);
            
            $this->setupGridColumnGroup($result);
            $this->attachGridEventHandlers($result);
            
            return $result;
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
            $this->setDescription('Management of orders');
            $this->setDetailedDescription('Management of orders');
    
            return $result;
        }
     
        protected function setClientSideEvents(Grid $grid) {
    
        }
    
        protected function doRegisterHandlers() {
            $detailPage = new order_order_bookPage('order_order_book', $this, array('order_id'), array('order_id'), $this->GetForeignKeyFields(), $this->CreateMasterDetailRecordGrid(), $this->dataset, GetCurrentUserPermissionSetForDataSource('order.order_book'), 'UTF-8');
            $detailPage->SetRecordPermission(GetCurrentUserRecordPermissionsForDataSource('order.order_book'));
            $detailPage->SetTitle('Order details');
            $detailPage->SetMenuLabel('Order details');
            $detailPage->SetHeader(GetPagesHeader());
            $detailPage->SetFooter(GetPagesFooter());
            $detailPage->SetHttpHandlerName('order_order_book_handler');
            $handler = new PageHTTPHandler('order_order_book_handler', $detailPage);
            GetApplication()->RegisterHTTPHandler($handler);$detailPage = new order_order_historyPage('order_order_history', $this, array('order_id'), array('order_id'), $this->GetForeignKeyFields(), $this->CreateMasterDetailRecordGrid(), $this->dataset, GetCurrentUserPermissionSetForDataSource('order.order_history'), 'UTF-8');
            $detailPage->SetRecordPermission(GetCurrentUserRecordPermissionsForDataSource('order.order_history'));
            $detailPage->SetTitle('Order history');
            $detailPage->SetMenuLabel('Order history');
            $detailPage->SetHeader(GetPagesHeader());
            $detailPage->SetFooter(GetPagesFooter());
            $detailPage->SetHttpHandlerName('order_order_history_handler');
            $handler = new PageHTTPHandler('order_order_history_handler', $detailPage);
            GetApplication()->RegisterHTTPHandler($handler);//
            // View column for shipping_address field
            //
            $column = new TextViewColumn('shipping_address', 'shipping_address', 'Shipping Address', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'orderGrid_shipping_address_handler_list', $column);
            GetApplication()->RegisterHTTPHandler($handler);//
            // View column for user_fullname field
            //
            $column = new TextViewColumn('user_id', 'user_id_user_fullname', 'User', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'orderGrid_user_id_user_fullname_handler_list', $column);
            GetApplication()->RegisterHTTPHandler($handler);//
            // View column for shipping_address field
            //
            $column = new TextViewColumn('shipping_address', 'shipping_address', 'Shipping Address', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'orderGrid_shipping_address_handler_print', $column);
            GetApplication()->RegisterHTTPHandler($handler);//
            // View column for user_fullname field
            //
            $column = new TextViewColumn('user_id', 'user_id_user_fullname', 'User', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'orderGrid_user_id_user_fullname_handler_print', $column);
            GetApplication()->RegisterHTTPHandler($handler);//
            // View column for shipping_address field
            //
            $column = new TextViewColumn('shipping_address', 'shipping_address', 'Shipping Address', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'orderGrid_shipping_address_handler_compare', $column);
            GetApplication()->RegisterHTTPHandler($handler);//
            // View column for user_fullname field
            //
            $column = new TextViewColumn('user_id', 'user_id_user_fullname', 'User', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'orderGrid_user_id_user_fullname_handler_compare', $column);
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
            $handler = new DynamicSearchHandler($lookupDataset, $this, 'filter_builder_current_status_F2_search', 'F1', 'F2', null, 20);
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
            // View column for shipping_address field
            //
            $column = new TextViewColumn('shipping_address', 'shipping_address', 'Shipping Address', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'orderGrid_shipping_address_handler_view', $column);
            GetApplication()->RegisterHTTPHandler($handler);//
            // View column for user_fullname field
            //
            $column = new TextViewColumn('user_id', 'user_id_user_fullname', 'User', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'orderGrid_user_id_user_fullname_handler_view', $column);
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
        $Page = new orderPage("order", "orders-list.php", GetCurrentUserPermissionSetForDataSource("order"), 'UTF-8');
        $Page->SetTitle('Orders');
        $Page->SetMenuLabel('List of orders');
        $Page->SetHeader(GetPagesHeader());
        $Page->SetFooter(GetPagesFooter());
        $Page->SetRecordPermission(GetCurrentUserRecordPermissionsForDataSource("order"));
        GetApplication()->SetMainPage($Page);
        GetApplication()->Run();
    }
    catch(Exception $e)
    {
        ShowErrorPage($e);
    }
	
