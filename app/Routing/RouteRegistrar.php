<?php


namespace App\Routing;


use Illuminate\Contracts\Routing\Registrar as Router;

class RouteRegistrar extends \Laravel\Passport\RouteRegistrar
{
    public function __construct(Router $router)
    {
        parent::__construct($router);
    }

    public function customAll()
    {
        $this->forAuthorization();
        $this->forAccessTokens();
        $this->forWebAccessTokens();
        $this->forTransientTokens();
        $this->forClients();
        $this->forPersonalAccessTokens();
    }

    /**
     * Register the routes for retrieving and issuing access tokens.
     *
     * @return void
     */
    public function forAccessTokens()
    {
        $this->router->post('/token', [
            'uses' => 'AccessTokenController@issueToken',
            'as' => 'passport.token',
            'middleware' => ['throttle', 'defaultGrantTypeAndScope'],
        ]);
    }

    public function forWebAccessTokens()
    {
        $this->router->group(['middleware' => ['web', 'auth']], function ($router) {
            $router->get('/tokens', [
                'uses' => 'AuthorizedAccessTokenController@forUser',
                'as' => 'passport.tokens.index',
            ]);

            $router->delete('/tokens/{token_id}', [
                'uses' => 'AuthorizedAccessTokenController@destroy',
                'as' => 'passport.tokens.destroy',
            ]);
        });
    }
}
