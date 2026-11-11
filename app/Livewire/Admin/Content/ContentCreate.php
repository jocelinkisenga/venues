<?php 
namespace App\Livewire\Admin\Content;

use App\Mail\NewsLetterMail;
use App\Models\Content;
use App\Models\NewsLetter;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;

class ContentCreate extends Component
{
    use WithFileUploads;

    public string $title = '';
    public string $type = 'blog';
    public string $excerpt = '';
    public string $content = '';
    public string $access_level = 'free';
    
    public $thumbnail;
    public $document;
    public $video; 

    
    protected function rules()
    {
        $rules = [
            'title' => 'required|min:3',
            'type' => 'required|in:blog,video,document,pack',
            'content' => 'required',
            'thumbnail' => 'nullable|image|max:2048',
        ];

        
        if ($this->type === 'document') {
            $rules['document'] = 'nullable|mimes:pdf|max:10240'; 
        } elseif ($this->type === 'video') {
            $rules['video'] = 'nullable|mimes:mp4,mov,avi|max:51200';
        }

        return $rules;
    }

    public function save()
    {
        $this->validate();

        $thumbnailPath = $this->thumbnail ? $this->thumbnail->store('thumbnails', 'public') : null;
        $documentPath = null;
        $videoPath = null;

        
        if ($this->type === 'document' && $this->document) {
            $documentPath = $this->document->store('documents', 'public');
        } elseif ($this->type === 'video' && $this->video) {
            $videoPath = $this->video->store('videos', 'public');
        }

        Content::create([
            'title' => $this->title,
            'slug' => Str::slug($this->title),
            'type' => $this->type,
            'excerpt' => $this->excerpt,
            'content' => $this->content,
            'access_level' => $this->access_level,
            'thumbnail' => $thumbnailPath,
            'document_path' => $documentPath,
            'video_url' => $videoPath, 
        ]);

        session()->flash('success', 'Content created successfully.');

                $subscribers = NewsLetter::whereSubscribed(true)->get();

            foreach ($subscribers as $subscriber) {
                Mail::to($subscriber->email)
                    ->send(new NewsLetterMail($this->title, $this->excerpt));
            }

        return redirect()->to('/admin/content');
    }

    public function render()
    {
        return view('livewire.admin.content.content-create');
    }
}