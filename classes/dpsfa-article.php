<?php
/**
 *
 * Package: Folio Authoring for WordPress
 * Class : Article
 * Description: This class contains article specific parameters and functions.
 */
 
namespace DPSFolioAuthor;
 
if( $_SERVER[ 'SCRIPT_FILENAME' ] == __FILE__ )
	die( 'Access denied.' );

if(!class_exists('DPSFolioAuthor\Article')) { 
    
    class Article extends Content{
	    
	    public $author = '';
	    public $authorUrl = '';
	    public $articleText = '';
	    public $isAd = FALSE;
	    public $adType = '';
	    public $adCategory = '';
	    public $advertiser = '';
	    public $accessState = 'metered';
	    public $hideFromBrowsePage = FALSE;
	    public $isTrustedContent = FALSE;

	    // CMS Specific
	    public $articleFolio;
	    public $body;
	    public $template;
	    public $cmsPreview;

	    
	    public function __construct($data = array()) {
			parent::__construct();
			
			$this->entityType = 'article';
			
			if(!empty($data['id'])){
				$CMS = new CMS();
				$this->id = $data['id'];
				$this->populate_object( $CMS->get_entity_data($this) );
			}
			$this->populate_object($data);
			
			if(empty($this->template)){
				$templates = new Templates();
				$defaultTemplate = $templates->get_default();
				$this->template = $defaultTemplate['path'];
			}
			
			if(!$this->isAd){
				$this->adType = null;
				$this->advertiser = null;
				$this->adCategory = null;
			}
		}
		
		public function push_article(){
			// Bundle article into .article file
			$bundlr = new Bundlr();
			$article = $bundlr->bundle($this);
			
			// Push article to adobe's cloud
			$adobe = new Adobe();
			$adobe->upload_article_folio($this, $article);
		}
		
		public function sync(){
			$CMS = new CMS();
			$CMS->sync_from_origin($this);
		}
						
		public function apiAllowed(){
			return array(
				'author', 
				'authorUrl', 
				'articleText', 
				'isAd', 
				'adType', 
				'adCategory', 
				'advertiser', 
				'accessState', 
				'hideFromBrowsePage', 
				'isTrustedContent', 
				'entityType', 
				'entityName', 
				'title', 
				'_links',
				'shortTitle', 
				'abstract', 
				'shortAbstract', 
				'keywords',
				'internalKeywords', 
				'department', 
				'category', 
				'importance', 
				'socialShareUrl', 
				'availabilityDate'
			);
		}
		
		public $internalFields = array(
			'_links',
			'version',
			'contentVersion',
			'url',
			'modified',
			'created',
			'published',
			'userData',
			'articleFolio'
		);
			        
    } // END class Article 
}