<?php
namespace App\Controllers\Admin;

use \Core\View;
use \App\Models\Admin\Gallery as GalleryModel;
use \App\Auth;
class Gallery extends \Core\Controller{

	public function before(){
		if(!Auth::isLoggedIn()){
			Auth::rememberPage();
			$this->redirect("/admin/login/");
		}
		return true;
	}

	protected $limit = 20;
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
			$this->redirect('/admin/gallery/page/1/');
		}
		View::renderTemplate('Admin/Gallery/index.html',
			[
				'title'     => 'Gallery' ,
				'images'    => $images,
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
	
	public function deleteAction(){
		$s_no = $this->route_params['var'];
		GalleryModel::delete($s_no);
		$this->redirect('/admin/gallery/');
	}
	public function ajaxDeleteAction(){
		$s_no = $this->route_params['var'];
		GalleryModel::delete($s_no);
	}
	public function uploadAction(){
		$error = GalleryModel::upload($_FILES['images']);
		$this->redirect('/admin/gallery/');
	}


}
?>
