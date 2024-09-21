<?php

namespace App\Http\Livewire;

use App\Models\User;
use App\Models\Conversation;
use Livewire\Component;

class Users extends Component
{
  public function message($userId)
  {
    // dd($userId); dd is used to dump the (data basically it is used for debugging)

    $authenticatedUserId = auth()->id();

    # checks for existing conversation
    $existingConversation = Conversation::where(function ($query) use ($authenticatedUserId, $userId) {
      $query->where('sender_id', $authenticatedUserId)->where('receiver_id', $userId);
    })->orWhere(function ($query) use ($authenticatedUserId, $userId) {
      $query->where('sender_id', $userId)->where('receiver_id', $authenticatedUserId);
    })->first();

    if ($existingConversation) {
      return redirect()->route('chat', ['query' => $existingConversation->id]);
    }

    #create conversation

    $createdConversation = Conversation::create([
      'sender_id' => $authenticatedUserId,
      'receiver_id' => $userId,
    ]);
    return redirect()->route('chat', ['query' => $createdConversation->id]);
  }
  public function render()
  {
    return view('livewire.users', ['users' => User::where('id', '!=', auth()->id())->get()]);
  }
}
