<?php

/**
 * @package    davidix
 * @subpackage Base
 */

defined('_JEXEC') || die('=;)');

/**
 * davidix Controller.
 *
 * @package    davidix
 * @subpackage Controllers
 */
class dixRest  {


		public	$com_name	=	"com_content";		//Component Name
		public	$moc_name	=	"ContentModel";		//Model Class Name
		public	$mod_name	=	"Articles";			//Model Name
		public  $mos_name	=	"Article";	

		
		public function items()
		{
			JLoader::import('joomla.application.component.model');
			$app = JFactory::getApplication();
			$this->getTyp();
			$Act=$app->input->get('act');
			
			if($app->input->get('iid')) $this->item();
			
			switch($Act)
			{
				case "cats":
				$this->itemsCat();
				break;
			}
			
			
			JModelLegacy::addIncludePath(JPATH_SITE.'/components/'.$this->com_name.'/models', $this->moc_name);
			$items = JModelLegacy::getInstance($this->mod_name, $this->moc_name);
			
			//populateState
			/***********************************************/
			$items->setState('filter.category_id', $app->input->get('catid'));
			
			$items->setState('filter.tag', $app->input->get('filter_tag', 0, 'uint'));			
			
			$items->setState('list.limit', $app->input->get('limit', $app->get('list_limit', 0), 'uint'));

			$items->setState('list.start', $app->input->get('limitstart', 0, 'uint'));
			
			$items->setState('filter.language', $app->input->get('language', 0, 'uint'));	

			$items->setState('params', $app->getParams());
			$user = JFactory::getUser();

			if ((!$user->authorise('core.edit.state', $this->com_name)) && (!$user->authorise('core.edit', $this->com_name)))
			{
				$items->setState('filter.published', 1);
			}

			$items->setState('filter.language', JLanguageMultilang::isEnabled());

			$items->setState('layout', $app->input->getString('layout'));
			//populateState
			/***********************************************/
			$res = $items->getItems();
			$doc=JFactory::getDocument();
			$doc->setTitle("OK");
			header('Content-Type: application/json');
			echo json_encode($res);
			jexit();
	}
	
		public function item()
		{
			JLoader::import('joomla.application.component.model');
			JTable::addIncludePath('/components/com_content/');
			$app = JFactory::getApplication();
			$this->getTyp();
			$Act=$app->input->get('act');
			$id=$app->input->get('iid');
			
			switch($Act)
			{
				case "cats":
				$this->itemsCat();
				break;
			}
			
			JModelLegacy::addIncludePath(JPATH_SITE.'/components/'.$this->com_name.'/models', $this->moc_name);
			$item = JModelLegacy::getInstance($this->mos_name, $this->moc_name);
			
			$res = $item->getItem($id);
			$doc=JFactory::getDocument();
			$doc->setTitle("OK");
			header('Content-Type: application/json');
			echo json_encode($res);
			jexit();
	}
	
	
	public function itemsCat()
	{	
		$app = JFactory::getApplication();
		
		JLoader::import('joomla.application.component.model');
		JModelLegacy::addIncludePath(JPATH_SITE.'/components/'.$this->com_name.'/models', $this->moc_name);
		$itemsCat = JModelLegacy::getInstance('Categories', $this->moc_name);

		$itemsCat->setState('filter.parentId', $app->input->getInt('id'));

		//$ArticlesCat->setState('params', $app->getParams());
		$user = JFactory::getUser();

		if ((!$user->authorise('core.edit.state', 'com_content')) && (!$user->authorise('core.edit', 'com_content')))
		{
			$itemsCat->setState('filter.published', 1);
		}

		//$itemsCat->setState('filter.language', JLanguageMultilang::isEnabled());

		$itemsCat->setState('layout', $app->input->getString('layout'));
		//populateState
		/***********************************************/
		$baz = $itemsCat->getItems();
		$doc=JFactory::getDocument();
		$doc->setTitle("OK");
		header('Content-Type: application/json');
		echo json_encode($baz);
		jexit();
	}
	public function getTyp()
	{
		$app = JFactory::getApplication();
		$Typ=$app->input->get('typ');
		switch($Typ)
		{
			case "content" :
			$this->com_name	=	"com_content";				//Component Name
			$this->moc_name	=	"ContentModel";				//Model Class Name
			$this->mod_name	=	"Articles";					//Model Name
			$this->mos_name	=	"Article";					//Model Name Single 			
			break;
			
			case "worktracker" :
			$this->com_name	=	"com_worktracker";			//Component Name
			$this->moc_name	=	"WorktrackerModel";			//Model Class Name
			$this->mod_name	=	"Activities";				//Model Name
			$this->mos_name	=	"Activitie";				//Model Name Single 
			break;
			
			case "banners" :
			$this->com_name	=	"com_banners";				//Component Name
			$this->moc_name	=	"BannersModel";				//Model Class Name
			$this->mod_name	=	"Banners";					//Model Name
			$this->mos_name	=	"Banner";					//Model Name Single 			
			break;
			
			case "tags" :
			
			$this->com_name	=	"com_tags";					//Component Name
			$this->moc_name	=	"TagsModel";				//Model Class Name
			$this->mod_name	=	"Tags";						//Model Name
			$this->mos_name	=	"Tag";						//Model Name Single 
			break;
			
			
		}
	}


    //http://YOURSITE.COM/index.php?option=com_davidix&task=login&username=test&pass=test
    public function login() {

        $app = JFactory::getApplication();

        $credentials = array(
            'username' => $app->input->get('username', '', 'USERNAME'),
            'password' => $app->input->get('pass', '', 'STRING')
        );

        if ($app->login($credentials)) {
            // Success
            $user = JFactory::getUser();
            $data = array(
                'message' => 'success',
                'id' => $user->id,
                'username' => $user->username,
                'name' => $user->name,
                'email' => $user->email,
                'group' => $user->groups
            );
        } else {
            // login failed
            $data = array(
                'message' => 'login failed'
            );
        }
        header('Content-Type: application/json');
        echo json_encode($data);
        jexit();
    }

    //http://YOURSITE.COM/index.php?option=com_davidix&task=registration&name=NAME&username=USERNAME&passwd=PASSWORD&email=EMAIL
    public function registration() {

        $input = $this->input;

        $name = $input->get('name', '', 'STRING');
        $username = $input->get('username', '', 'USERNAME');
        $passwd = $input->get('passwd', '', 'STRING');
        $email = $input->get('email', '', 'STRING');

        $requestData = array(
            "name" => $name,
            "username" => $username,
            "password1" => $passwd,
            "email1" => $email,
        );

        include_once JPATH_ROOT . '/components/com_users/models/registration.php';
        JFactory::getLanguage()->load('com_users');
        $model = new UsersModelRegistration();
        $register = $model->register($requestData);

        if ($register === false) {
            $status = $model->getError();
        } else {
            $status = "Success";
        }

        $message = array(
            'message' => $status
        );

        header('Content-Type: application/json');
        echo json_encode($message);
        jexit();
    }

    //index.php?option=com_davidix&task=getEasyblog

    public function getEasyblog() {
        if (!file_exists(JPATH_ROOT . "/administrator/components/com_easyblog/includes/easyblog.php")) {
            jexit("You don't have install EasyBlog");
        }
        include_once JPATH_ROOT . "/administrator/components/com_easyblog/includes/easyblog.php";
        include_once JPATH_ROOT . "/administrator/components/com_easyblog/models/categories.php";

        $model = new EasyBlogModelCategories();
        $items = $model->getCategoriesHierarchy();
        $output = array();

        if ($items && !$this->input->get("catid") && !$this->input->get("id")) {

            foreach ($items as $item) {
                $item = get_object_vars($item);
                if ($item['avatar']) {
                    $item['avatar'] = "/images/easyblog_cavatar/" . $item['avatar'];
                }
                $output[] = $item;
            }
        } elseif ($this->input->get("catid")) {
            include_once JPATH_ROOT . '/administrator/components/com_easyblog/models/blog.php';
            $model = new EasyBlogModelBlog();
            $items = $model->getBlogsBy('', '', '', 0, EBLOG_FILTER_PUBLISHED, null, true, '', false, false, true, '', $this->input->get("catid"), null, 'listlength', '');
            foreach ($items as $item) {
                $output[] = get_object_vars($item);
            }
        } elseif ($this->input->get("id")) {
            $post = EB::post($this->input->get("id"));
            $output = get_object_vars($post->original);
            $output['intro'] = htmlspecialchars($post->getContent(), ENT_QUOTES);
            $output['image'] = $post->getImage();
            $output['videos'] = $post->videos;
            $output['author'] = $post->getAuthor()->nickname;
            $output['author_link'] = $post->getAuthor()->getPermalink();
            $output['author_avatar'] = $post->getAuthor()->avatar;
            $output['comments'] = $post->getComments();
            $output['custom_fields'] = $post->getCustomFields();

            if ($output['comments']) {
                foreach ($output['comments'] as $comment) {
                    if (empty($comment->name)) {
                        $comment->name = $comment->author->nickname;
                    }
                    unset($comment->author);
                }
            }
        }

        header('Content-Type: application/json');
        echo json_encode($output);
        jexit();
    }

    //http://YOURSITE.COM/index.php?option=com_davidix&task=getkunena
    public function getkunena() {
        if (!file_exists(JPATH_ROOT . '/libraries/kunena/attachment/helper.php')) {
            jexit("You don't have install Kunena");
        }
        include_once JPATH_ROOT . '/components/com_kunena/models/category.php';
        include_once JPATH_ROOT . '/libraries/kunena/attachment/helper.php';

        $output = array();
        $model = new KunenaModelCategory();
        $items = KunenaForumCategoryHelper::getCategories();

        $input = $this->input;

        if ($items && !$input->get("catid") && !$input->get("id")) {
            foreach ($items as $item) {
                $data = get_object_vars($item);
                $output[] = $data;
            }
        } elseif ($input->get("catid")) {
            $output = array();
            $model->setState('item.id', $input->get("catid", 1));
            $items = $model->getTopics();
            foreach ($items as $item) {
                $data = get_object_vars($item);
                $output[] = $data;
            }
        } elseif ($input->get("id")) {
            $output = array();
            include_once JPATH_ROOT . '/components/com_kunena/models/topic.php';
            include_once JPATH_ROOT . '/libraries/kunena/attachment/helper.php';
            $model = new KunenaModelTopic();
            $model->setState('item.mesid', $input->get("catid", 1));
            $items = $model->getMessages();
            foreach ($items as $item) {
                $data = get_object_vars($item);
                $data['attachment'] = KunenaAttachmentHelper::getByMessage($item->id);
                $output[] = $data;
            }
        }

        header('Content-Type: application/json');
        echo json_encode($output);
        jexit();
    }

    //http://YOURSITE.COM/index.php?option=com_davidix&task=getK2
    public function getK2() {
        if (!file_exists(JPATH_ROOT . '/components/com_k2/models/itemlist.php') || !file_exists(JPATH_ROOT . '/modules/mod_k2_content/helper.php')) {
            jexit("You must need to install K2 component & module");
        }
        include_once JPATH_ROOT . '/components/com_k2/models/itemlist.php';

        $output = array();
        $model = new K2ModelItemlist();
        $db = JFactory::getDBO();
        $query = "SELECT id,parent,name,description,alias,image,language FROM #__k2_categories WHERE published=1  AND trash=0";
        $db->setQuery($query);
        $items = $db->loadObjectList();

        if ($items && !$this->input->get("catid") && !$this->input->get("id")) {
            foreach ($items as $item) {
                $item = get_object_vars($item);
                if ($item["image"]) {
                    $item["image"] = "/media/k2/categories/" . $item["image"];
                }
                $output[] = $item;
            }
        } elseif ($this->input->get("catid")) {
            $model->set("task", "category");
            $model->set("id", $this->input->get("catid"));
            $total = $model->countCategoryItems($this->input->get("catid"));

            include_once JPATH_ROOT . '/modules/mod_k2_content/helper.php';
            $module = JModuleHelper::getModule('mod_k2_content');
            $params = new JRegistry($module->params);
            $params['itemImage'] = 1;
            $params['itemCount'] = (int) $total;
            $params['itemIntroText'] = 1;
            $params['itemVideo'] = 1;
            $items = modK2ContentHelper::getItems($params);
            foreach ($items as $item) {
                $item = get_object_vars($item);
                if ($item['params'] || $item['categoryparams']) {
                    unset($item['params']);
                    unset($item['categoryparams']);
                }
                if ($item['catid'] == $this->input->get("catid")) {
                    $output[] = $item;
                }
            }
            //
        } elseif ($this->input->get("id")) {

            include_once JPATH_ROOT . '/modules/mod_k2_content/helper.php';
            $module = JModuleHelper::getModule('mod_k2_content');
            $params = new JRegistry($module->params);
            $params['itemImage'] = 1;
            $params['itemCount'] = (int) $total;
            $params['itemIntroText'] = 1;
            $params['itemVideo'] = 1;
            $params['items'] = $this->input->get("id");
            $items = modK2ContentHelper::getItems($params);

            foreach ($items as $item) {
                $item = get_object_vars($item);
                if ($item['params'] || $item['categoryparams']) {
                    unset($item['params']);
                    unset($item['categoryparams']);
                }
                if ($item['id'] == $this->input->get("id")) {
                    $output[] = $item;
                }
            }
        }

        header('Content-Type: application/json');
        echo json_encode($output);
        jexit();
    }

    //http://YOURSITE.COM/index.php?option=com_davidix&task=getVM&lan=en_gb
    public function getVM() {

        if (!file_exists(JPATH_ROOT . '/administrator/components/com_virtuemart/helpers/config.php')) {

            jexit("You don't have install VM");
        }
        include_once JPATH_ROOT . '/administrator/components/com_virtuemart/helpers/config.php';
        include_once JPATH_ROOT . '/administrator/components/com_virtuemart/models/category.php';
        include_once JPATH_ROOT . '/administrator/components/com_virtuemart/models/media.php';

        $output = array();
        VmConfig::$vmlang = $this->input->get("lan", "en_gb", "STRING");
        $model = new VirtueMartModelCategory();
        $items = $model->getCategories();
        $mediaModel = new VirtueMartModelMedia();

        if ($items && !$this->input->get("catid") && !$this->input->get("id")) {
            foreach ($items as $key => $item) {

                $item = get_object_vars($item);
                $item['media'] = $mediaModel->getFiles("", "", "", $item['virtuemart_category_id']);
                $output[] = $item;
            }
        } elseif ($this->input->get("catid")) {
            include_once JPATH_ROOT . '/administrator/components/com_virtuemart/models/product.php';
            $model = new VirtueMartModelProduct();
            $items = $model->getProductsInCategory($this->input->get("catid"));

            foreach ($items as $key => $item) {

                $item = get_object_vars($item);
                $item['media'] = $mediaModel->getFiles("", "", $item['virtuemart_product_id']);
                //echo $item['virtuemart_product_id'];
                $output[] = $item;
            }
        } elseif ($this->input->get("id")) {
            include_once JPATH_ROOT . '/administrator/components/com_virtuemart/models/product.php';
            $model = new VirtueMartModelProduct();
            $item = get_object_vars($model->getProduct($this->input->get("id")));
            $item['media'] = $mediaModel->getFiles("", "", $item['virtuemart_product_id']);
            $output[] = $item;
        }

        header('Content-Type: application/json');
        echo json_encode($output);
        jexit();
    }

    // http://YOURSITE.COM/index.php?option=com_davidix&task=getHika
    public function getHika() {

        if (!file_exists(JPATH_ROOT . '/administrator/components/com_hikashop/helpers/helper.php')) {
            jexit("You don't have Hikashop installed");
        }
        include_once JPATH_ROOT . '/administrator/components/com_hikashop/helpers/helper.php';
        include_once JPATH_ROOT . '/administrator/components/com_hikashop/classes/category.php';

        $output = array();
        $model = new hikashopCategoryClass();
        $items = $model->getList();
        if ($items && !$this->input->get("catid") && !$this->input->get("id")) {

            foreach ($items as $item) {
                $item = get_object_vars($item);
                $item['media'] = $this->getHikaImages($item['category_id']);
                $output[] = $item;
            }
        } elseif ($this->input->get("catid")) {
            $db = JFactory::getDbo();
            $query = "SELECT product_id FROM #__hikashop_product_category WHERE" . $db->quoteName('category_id') . " = " . $db->quote($this->input->get("catid"));
            $db->setQuery($query);
            $items = $db->loadColumn();

            include_once JPATH_ROOT . '/administrator/components/com_hikashop/classes/product.php';
            $model = new hikashopProductClass();
            if ($model->getProducts($items)) {
                $products = $model->all_products;
                foreach ($products as $product) {
                    $output[] = $product;
                }
            }
        } elseif ($this->input->get("id")) {
            include_once JPATH_ROOT . '/administrator/components/com_hikashop/classes/product.php';
            $model = new hikashopProductClass();
            $model->getProducts($this->input->get("id"));
            $product = $model->products;
            $output = $product[$this->input->get("id")];
        }
        header('Content-Type: application/json');
        echo json_encode($output);
        jexit();
    }

    protected function getHikaImages($id) {
        include_once JPATH_ROOT . '/administrator/components/com_hikashop/helpers/helper.php';
        include_once JPATH_ROOT . '/administrator/components/com_hikashop/helpers/image.php';
        $db = JFactory::getDBO();
        $query = "SELECT file_path FROM #__hikashop_file WHERE file_ref_id={$id} ";
        $db->setQuery($query);
        $items = $db->loadAssocList();
        $output = array();
        $model = new hikashopImageHelper();
        foreach ($items as $item) {
            $output[] = get_object_vars($model->getThumbnail($item['file_path']));
        }
        return $output;
    }
    
    //http://YOURSITE.COM/index.php?option=com_davidix&task=getAdsm
    //Image path: http://YOURSITE.COM/images/com_adsmanager/contents/FILE_NAME
    public function getAdsm() {

        if (!file_exists(JPATH_ROOT . '/components/com_adsmanager/lib/core.php')) {
            jexit("You don't have AdsManager installed");
        }
        include_once JPATH_ROOT . '/components/com_adsmanager/lib/core.php';
        include_once JPATH_ROOT . '/administrator/components/com_adsmanager/models/category.php';
        include_once JPATH_ROOT . '/administrator/components/com_adsmanager/models/content.php';

        $output = array();
        $model = new AdsmanagerModelCategory();
        $items = $model->getCategories(true);
        $input = $this->input;

        if ($items && !$input->get("catid") && !$input->get("id")) {
            foreach ($items as $item) {
                $data = get_object_vars($item);
                $output[] = $data;
            }
        } elseif ($input->get("catid")) {
            $model = new AdsmanagerModelContent();
            $filters['category'] = $catid;
            $items = $model->getContents($filters);

            foreach ($items as $item) {
                $data = get_object_vars($item);
                $output[] = $data;
            }
        } elseif ($input->get("id")) {
            $model = new AdsmanagerModelContent();
            $item = $model->getContent($input->get("id"), true);
            $data = get_object_vars($item);
            $output[] = $data;
        }

        header('Content-Type: application/json');
        echo json_encode($output);
        jexit();
    }

}
