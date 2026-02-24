<?php

declare(strict_types=1);

namespace App\Filament\Idp\Pages;

use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Livewire\Component;

class OAuthAuthorize extends Component implements HasSchemas
{
    // use Forms\Concerns\InteractsWithForms;
    use InteractsWithActions;
    use InteractsWithSchemas;

    protected static bool $shouldRegisterNavigation = false;

    protected static string|BackedEnum|null $navigationIcon = 'myicon-rag';

    protected string $view = 'filament.idp.pages.o-auth-authorize';

    public $client;

    public $request;

    public $authToken;

    public $scopes;

    public function mount($client, $request, $authToken, $scopes): void
    {
        dd('enterOAuthAuthorize::mount');
        $this->client = $client;
        $this->request = $request;
        $this->authToken = $authToken;
        $this->scopes = $scopes;
        // dd($client, $request, $authToken, $scopes);
        $this->form->fill([
            'state' => $request->state,
            'client_id' => $client->id,
            'auth_token' => $authToken,
        ]);
    }

    protected function getFormSchema(): array
    {
        return [
            TextInput::make('state'),
            TextInput::make('client_id'),
            TextInput::make('auth_token'),
        ];
    }

    // public function authorize(): void
    // {
    //     request()->merge($this->form->getState());
    //     request()->setMethod('POST');
    //     app()->call('Laravel\Passport\Http\Controllers\ApproveAuthorizationController@approve');
    //     redirect()->away($this->request->redirect_uri);
    // }

    // public function deny(): void
    // {
    //     request()->merge($this->form->getState());
    //     request()->setMethod('DELETE');
    //     app()->call('Laravel\Passport\Http\Controllers\DenyAuthorizationController@deny');
    //     redirect()->away($this->request->redirect_uri);
    // }

    protected function getFormActions(): array
    {
        return [
            Action::make('Authorize')
                ->color('primary')
                ->action(fn () => app()->call('Laravel\Passport\Http\Controllers\ApproveAuthorizationController@approve')),

            Action::make('Cancel')
                ->color('danger')
                ->action(fn () => $this->deny()),
        ];
    }
}
