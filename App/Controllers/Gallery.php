<?php
namespace App\Controllers;
/*
 * Gallery
 */
use \Core\View;
use \App\Models\Gallery as GalleryModel;
class Gallery extends \Core\Controller{


	protected $limit = 9;
	/*
	 * parameters are passed through 
	 * constructors of Base class.
	 * \Core\Controller
	 * @protected route_params['num'] - contains page number
	 */


	public function galleryAction($page = 1){
		$total = GalleryModel::fetchNumber();
		$tot_page = ceil($total/$this->limit);
		$images = GalleryModel::fetchImages($page,$this->limit);
		if($images==false){
			$this->redirect('/gallery/page/1/');
		}		
		View::renderTemplate('Gallery/gallery.html',
			[
				'title'     => 'Gallery' ,
				'images'    => $images ,
				'total'     => $total ,
				'page'      => $page ,
				'totalPage' => $tot_page

		]);
	}
	public function indexAction(){
		self::galleryAction();
	}

	public function pageAction(){
		$page = $this->route_params['num'];
		self::galleryAction($page);
	}



}

?>