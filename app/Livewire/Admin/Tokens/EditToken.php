<?php

namespace App\Livewire\Admin\Tokens;

use App\Actions\IncrementTokens;
use App\Models\Token;
use Livewire\Component;

class EditToken extends Component
{
  public ?string $supplier;
  public $price;
  public $tokens;
  public $token;
  public $token_id;

  public function mount($tokenId)
  {
    $this->token = Token::findOrFail($tokenId);
    $this->token_id = $tokenId;
    $this->supplier = $this->token->supplier;
    $this->tokens = $this->token->entry_tokens;
    $this->price = $this->token->price;
  }

  protected function rules()
  {
    $rules = [
      "supplier" => "required|min:3",
      "price" => "required",
      "tokens" => "required",
    ];

    return $rules;
  }

  public function save(IncrementTokens $incrementTokens)
  {
    $this->validate();

    $lastTokens =
      Token::findOrFail($this->token_id)->value("total_tokens") ?? 0;

    $this->token->update([
      "entry_tokens" => $this->tokens,
      "price" => $this->price,
      "supplier" => $this->supplier,
      "total_tokens" => $lastTokens + (int) $this->tokens,
    ]);

    session()->flash("success", "Plan created successfully.");
    return redirect()->to("/admin/tokens");
  }

  public function render()
  {
    return view("livewire.admin.tokens.edit-token");
  }
}
