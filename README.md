
[![License](https://poser.pugx.org/laravel/framework/license.svg)](https://packagist.org/packages/laravel/framework)


Creating custom packages - Laravel 5.*
======
Custom packages is a simple practical example showing a way to build customized packages in Laravel. In this example you are going to go through every step of developing your own custom packages. 

##Folder structure
The first thing we need to do is set up our folder and name-spacing structure of our package, this is very important and it follows a standard know as PSR-4. Most packages that have this structure use the following path name:

```
vendor/package-name
```
where "package name" is your package name, such as, "simple-admin" and vendor would be the package folder name. However, you will be able to find the example package in the application root folder, which is a folder named "packages"
```
packages/simple-admin
```

##Using Composer
Now that we have our folder structure the first thing we need to do is create a composer.json file for our package. This is required so that we can make our package available to download to the public as well as define any dependencies the package requires.

In order to do that you need to go inside the “simple-admin” directory and run the following command

```
composer init
```

After following the various prompts you will end up with a composer.json file that looks similar to this:

```
{
    "name": "gocanto/simple-admin",
    "description": "A package which provides a simple UI for managing users",
    "authors": [
        {
            "name": "Gustavo Ocanto",
            "email": "gustavoocanto@gmail.com"
        }
    ],
    "minimum-stability": "dev",
    "require": {}
}
```

Before we move on from composer we still need to do one thing, autoload our package so that Laravel can use it. To do that open the other composer.json file which is found in the root of your Laravel application. Within the “psr-4” section of this file you will already see a namespace which autoloads the app directory, underneath add a name-space which points to the package we are building. In my case I am doing something like this:

```
"autoload": {
        "classmap": [
            "database"
        ],
        "psr-4": {
            "App\\": "app/",
            "Gocanto\\SimpleAdmin\\": "packages/gocanto/simple-admin/src"
        }
  }
```

Finally run the following in the root directory. This command allows Composer to pick up our new name-space and autoload it for us. This is all we need for know so let’s move on

```
composer dumpautoload
```

##It is time to work in our service provider
Service Providers tell Laravel all about our custom package and let Laravel know what dependencies we need to bind and resolve to and from the IoC container. This in itself is another blog post entirely but for now we are just going to keep things simple.

In the terminal run the following command:

```
php artisan make:provider SimpleAdminServiceProvider
```
This command generates a Service Provider for us and places it by default in:
```
app/Providers
```

Copy it across into the “src” folder of your custom package and be sure to update the name-space to reflect the one you added to your root Composer file. Your Service Provider should look like this:

```
namespace Gocanto\SimpleAdmin;

use Illuminate\Support\ServiceProvider;

class SimpleAdminServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        if (is_dir(base_path() . '/resources/views/gocanto/simpleAdmin')) {
            $this->loadViewsFrom(base_path() . '/resources/views/gocanto/simpleAdmin', 'simpleAdmin');
        } else {
            $this->loadViewsFrom(__DIR__.'/views', 'simpleAdmin');
        }

        $this->publishes([
            __DIR__.'/views' => base_path('resources/views/gocanto/simpleAdmin'),
        ]);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        include __DIR__.'/routes.php';
    }
}
```

Finally we need to add our service provider to the array providers array in config/app like so:

```
Gocanto\SimpleAdmin\SimpleAdminServiceProvider::class
```

If you are interested in learning more about laravel services providers, you can click on the following link 
* [Laravel/ServicesProviders](http://laravel.com/docs/5.1/providers)

##Creating a controller for our package
We have to create a basic controller and place it in the “src” folder of our package directory it looks like this:

```
namespace Gocanto\SimpleAdmin;
use App\Http\Controllers\Controller;
use App\User;

class SimpleAdminController extends Controller {

  /**
  * Display a listing of the resource.
  *
  * @return Response
  */
  public function index()
  {
    $users = User::all();

    return view('simpleAdmin::admin')->with('users', $users);
  }
}
```

You will notice the name-spacing (Gocanto\SimpleAdmin) when we refer to a view in our package, this is done so as not to conflict with any views we are using in the rest of our app. In order to set that up go to your custom package Service Provider and add the following lines to the boot method:

```
if (is_dir(base_path() . '/resources/views/gocanto/simpleAdmin')) {
      $this->loadViewsFrom(base_path() . '/resources/views/gocanto/simpleAdmin', 'simpleAdmin');
} else {
      $this->loadViewsFrom(__DIR__.'/views', 'simpleAdmin');
}
```

Those linen tell Laravel where to look for when loading our custom views. We can also tell Laravel to publish our custom views to a specific folder so that whoever downloads our package can edit them without touching the code in your package directly. To do this add the following to the boot method:

```
$this->publishes([
    __DIR__.'/views' => base_path('resources/views/gocanto/simpleAdmin'),
]);
```

After someone downloads your package if they run

```
php artisan vendor:publish
```

your views will get put it the root resources folder as per the structure above. How awesome is that?!  

At this point we can create a “views” folder in the “src” directory and add in an admin.blade.php template file. It contains the following:

```
<div class="container">
	<h1>Package view :: You have {{ count($users) }} user(s)</h1>
	<div class="col-md-8">
		<ul class="list-group">
			@foreach($users as $user)
				<li class="list-group-item">
					{{ $user->first_name.' '.$user->last_name }}&nbsp;|&nbsp;<a href="mailto:{{ $user->email }}">{{ $user->email }}</a>
				</li>
			@endforeach
		</ul>
	</div>
</div>
```

It simply lists out all our users.

##Routes File
A package needs to be able to route and thankfully this is very easy to set up. You can simply create a routes file in the “src” directory and then add the following line to the register method of your Service Provider:

```
include __DIR__.'/routes.php';
```

My routes file contains just one route to the controller that we created earlier

```
Route::get('admin', 'gocanto\simpleAdmin\SimpleAdminController@index');
```

We have already autoloaded the files we need but how does Laravel’s IoC container know about our controller? At the moment it doesn’t so let’s fix that! Add the following to the register method of your Service Provider:

```
$this->app->make('Gocanto\SimpleAdmin\SimpleAdminController');
```

This line of code makes sure our controller get’s resolved out of the IoC container along with any dependencies it may have.

At this point if you go to /admin in your browser you should see a list of users in your Database. Package complete!


## Contact
* http://g-ocanto.com
* email: gustavoocanto@gmail.com
* Twitter: [@gocanto](https://twitter.com/gocanto "gocanto on twitter")
