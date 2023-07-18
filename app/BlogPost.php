<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BlogPost extends Model {
	
	protected $fillable = [
		'title',
		'content',
		'cover_photo',
		'publish_date',
		'enabled',
		'meta_title',
		'meta_description',
		'admin_id',
		'show_at_success_stories',
		'show_at_accreditation_page'
	];
	
	public function blogCategories() {
		return $this->belongsToMany(BlogCategory::class);
	}
	
	public function admin() {
		return $this->belongsTo(Admin::class);
	}
}
