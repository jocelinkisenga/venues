<?php

namespace App\Livewire\Admin\Content;

use App\Models\Content;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;

class ContentEdit extends Component
{
  use WithFileUploads;

  public string $title = "";
  public string $type = "blog";
  public string $excerpt = "";
  public string $content = "";
  public string $access_level = "free";
  public $singleContent;

  public $thumbnail;
  public $document;
  public $video;

  public function mount($contentId)
  {
    $this->singleContent = Content::findOrFail($contentId);
    $this->title = $this->singleContent->title;
    $this->excerpt = $this->singleContent->excerpt;
  }
  public function render()
  {
    return view("livewire.admin.content.content-edit");
  }

  public function save()
  {
    $thumbnailPath = $this->thumbnail
      ? $this->thumbnail->store("thumbnails", "public")
      : null;
    $documentPath = null;
    $videoPath = null;

    if ($this->type === "document" && $this->document) {
      $documentPath = $this->document->store("documents", "public");
    } elseif ($this->type === "video" && $this->video) {
      $videoPath = $this->video->store("videos", "public");
    }
    $this->singleContent->update([
      "title" => $this->title,
      "slug" => Str::slug($this->title),
      "type" => $this->type,
      "excerpt" => $this->excerpt,
      "content" => $this->content,
      "access_level" => $this->access_level,
      "thumbnail" => $thumbnailPath,
      "document_path" => $documentPath,
      "video_url" => $videoPath,
    ]);

    session()->flash("success", "Content created successfully.");
    return redirect()->to("/admin/content");
  }
}
