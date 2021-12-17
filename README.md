## 
## Description

- extending models
- customizable table names
- extending the filter to get a list of models
- extending a query for listing models using the event system
- console command to add to cron to optimize table data
- console command to activate/deactivate banners by time
- binding banners to models
- trait class to implement the relationship between your model and banners

## Install for Lumen

**1.** Open file `bootstrap/app.php` and add new service provider

```
$app->register(\ItDevgroup\LaravelBannerApi\Provider\BannerServiceProvider::class);
```

Uncommented strings

```
$app->withFacades();
$app->withEloquent();
```

Added after **$app->configure('app');**

```
$app->configure('banner_api');
```

**2.** Run commands

For creating config file

```
php artisan banner:api:publish --tag=config
```

Check the table names in the config "**config/banner_api.php**", in the **table** section

For creating migration file

```
php artisan banner:api:publish --tag=migration
```

For generate table

```
php artisan migrate
```

## Install for laravel

**1.** Open file **config/app.php** and search

```
    'providers' => [
        ...
    ]
```

Add to section

```
        \ItDevgroup\LaravelBannerApi\Provider\BannerServiceProvider::class,
```

Example

```
    'providers' => [
        ...
        \ItDevgroup\LaravelBannerApi\Provider\BannerServiceProvider::class,
    ]
```

**2.** Run commands

For creating config file

```
php artisan vendor:publish --provider="ItDevgroup\LaravelBannerApi\Provider\BannerServiceProvider" --tag=config
```

Check the table names in the config "**config/banner_api.php**", in the **table** section

For creating migration file

```
php artisan banner:api:publish --tag=migration
```

For generate table

```
php artisan migrate
```

## ENV variables

File .env

All ENV variables is optionals

Main type for main image (default: empty value)

```
BANNER_API_IMAGE_MAIN_TYPE=main
```

Folder for all images (default: banners)

```
BANNER_API_IMAGE_FOLDER=banners
```

Optimization: clearing images without banner (default: true)

```
BANNER_API_OPTIMIZATION_IMAGE_CLEAR=1
```

Optimization: clearing attach models without banner (default: true)

```
BANNER_API_OPTIMIZATION_ATTACH_MODEL_CLEAR=1
```

Optimization: clearing statistic clicks (default: true)

```
BANNER_API_OPTIMIZATION_STATISTIC_CLICK_CLEAR=1
```

Optimization: total number of months for storing statistics on clicks (default: 12)

```
BANNER_API_OPTIMIZATION_STATISTIC_CLICK_MONTH=12
```

## Custom model

if you need to extend the model, for example add methods or properties.

###### Step 1

Create custom model for BannerModel.

Example:

File: **app/CustomBannerModel.php**

Content:

```
<?php

namespace App;

class CustomBannerModel extends \ItDevgroup\LaravelBannerApi\Model\BannerModel
{
}
```

###### Step 2

Open **config/banner_api.php** and change parameter "model.banner", example:

Find:

```
'model' => [
    ...
    // this line
    'banner' => \ItDevgroup\LaravelBannerApi\Model\BannerModel::class,
    ...
],
```

```
// change this line
'banner' => \ItDevgroup\LaravelBannerApi\Model\BannerModel::class,
// to
'banner' => \App\CustomBannerModel::class,
```

###### Other models

Similarly, the rest of the models specified in the section "model.*" in the configuration are replaced

## Usage

#### Initialize service

```
$service = app(\ItDevgroup\LaravelBannerApi\BannerServiceInterface::class);
```

or injected

```
// use
use ItDevgroup\LaravelBannerApi\BannerServiceInterface;
// constructor
public function __construct(
    BannerServiceInterface $bannerService
)
```

further we will use the variable **$service**

### Banner position API

New model:

```
$bannerPosition = \ItDevgroup\LaravelBannerApi\Model\BannerPositionModel::register(
    'title'
);

$bannerPosition = $service->getBannerPositionHandler()->newModel('title');

$bannerPosition = new \ItDevgroup\LaravelBannerApi\Model\BannerPositionModel();
$bannerPosition->title = 'title';
```

List:

Event support - ItDevgroup\LaravelBannerApi\Event\BannerApiPositionListBuilderEvent

```
return $service->getBannerPositionHandler()->list();

$filter = new \ItDevgroup\LaravelBannerApi\Model\BannerPositionFilter();
$option = new \ItDevgroup\LaravelBannerApi\BannerRequestOption();
return $service->getBannerPositionHandler()->list($filter, $option);
```

###### return

```
Illuminate\Contracts\Pagination\LengthAwarePaginator
Illuminate\Database\Eloquent\Collection
ItDevgroup\LaravelBannerApi\Model\BannerPositionModel[]
```

By ID:

```
return $service->getBannerPositionHandler()->byId(1);
```

###### return

```
ItDevgroup\LaravelBannerApi\Model\BannerPositionModel
```

Save:

```
$bannerPosition = $service->getBannerPositionHandler()->byId(1); // or new model
return $service->getBannerPositionHandler()->save($bannerPosition);
```

###### return

```
(boolean)
```

Delete:

```
$bannerPosition = $service->getBannerPositionHandler()->byId(1);
return $service->getBannerPositionHandler()->delete($bannerPosition);
```

###### return

```
(boolean)
```

### Banner API

New model:

```
$banner = \ItDevgroup\LaravelBannerApi\Model\BannerModel::register(
    'title'
);

$banner = $service->getBannerHandler()->newModel('title');

$banner = new \ItDevgroup\LaravelBannerApi\Model\BannerModel();
$banner->title = 'title';
```

List:

Event support - ItDevgroup\LaravelBannerApi\Event\BannerApiBannerListBuilderEvent

```
return $service->getBannerHandler()->list();

$filter = new \ItDevgroup\LaravelBannerApi\Model\BannerFilter();
$option = new \ItDevgroup\LaravelBannerApi\BannerRequestOption();
return $service->getBanneHandler()->list($filter, $option);
```

###### return

```
Illuminate\Contracts\Pagination\LengthAwarePaginator
Illuminate\Database\Eloquent\Collection
ItDevgroup\LaravelBannerApi\Model\BannerModel[]
```

By ID:

```
return $service->getBannerHandler()->byId(1);
```

###### return

```
ItDevgroup\LaravelBannerApi\Model\BannerModel
```

Save:

```
$banner = $service->getBannerHandler()->byId(1); //or new model
return $service->getBannerHandler()->save($banner);
```

_With positions_

```
$positions = [1, 5, 10];
// or
$positions = [
    $service->getBannerPositionHandler()->byId(1),
    $service->getBannerPositionHandler()->byId(5),
    $service->getBannerPositionHandler()->byId(10),
];
$banner = $service->getBannerHandler()->byId(1); // or new model
return $service->getBannerHandler()->save($banner, $positions);
```

###### return

```
(boolean)
```

Delete:

```
$banner = $service->getBannerHandler()->byId(1);
return $service->getBannerHandler()->delete($banner);
```

###### return

```
(boolean)
```

### Banner image API

Custom folder for images (replace global setting from config):

```
$service->getBannerImageHandler()->setFolder('folder/subfolder');
```

New model:

```
$bannerImage = \ItDevgroup\LaravelBannerApi\Model\BannerImageModel::register(
    $banner,
    'img/file.jpg',
    'local'
);

$bannerImage = new \ItDevgroup\LaravelBannerApi\Model\BannerImageModel();
$bannerImage->path = 'img/file.jpg';

// with upload file for model
$file = $service->getBannerImageHandler()->upload(
    \Illuminate\Http\UploadedFile::fake()->image('image.jpg')
)
$bannerImage = $service->getBannerImageHandler()->newModelWithFile(
    $banner,
    $file
);

// with external link for image
$bannerImage = $service->getBannerImageHandler()->newModelWithUrl(
    $banner,
    'https://img/file.jpg'
);
```

List:

Event support - ItDevgroup\LaravelBannerApi\Event\BannerApiImageListBuilderEvent

```
return $service->getBannerImageHandler()->list();

$filter = new \ItDevgroup\LaravelBannerApi\Model\BannerImageFilter();
$option = new \ItDevgroup\LaravelBannerApi\BannerRequestOption();
return $service->getBannerImageHandler()->list($filter, $option);
```

###### return

```
Illuminate\Contracts\Pagination\LengthAwarePaginator
Illuminate\Database\Eloquent\Collection
ItDevgroup\LaravelBannerApi\Model\BannerImageModel[]
```

By ID:

```
return $service->getBannerImageHandler()->byId(1);
```

###### return

```
ItDevgroup\LaravelBannerApi\Model\BannerImageModel
```

Save:

```
$bannerImage = $service->getBannerImageHandler()->byId(1); // or new model
return $service->getBannerImageHandler()->save($bannerImage);
```

###### return

```
(boolean)
```

Delete:

```
$bannerImage = $service->getBannerImageHandler()->byId(1);
return $service->getBannerImageHandler()->delete($bannerImage);
```

###### return

```
(boolean)
```

## Banner attach model API

New model:

```
$attachModel = $service->getBannerAttachModelHandler()->newOrGetModel(
    $banner,
    \App\Models\User::find(1)
);
```

List by banner:

```
return $service->getBannerAttachModelHandler()->listByBanner(
    $banner
);
```

###### return

```
Illuminate\Database\Eloquent\Collection
ItDevgroup\LaravelBannerApi\Model\BannerAttachModel[]
```

List by model:

```
return $service->getBannerAttachModelHandler()->listByModel(
    \App\Models\User::find(1)
);
```

###### return

```
Illuminate\Database\Eloquent\Collection
ItDevgroup\LaravelBannerApi\Model\BannerAttachModel[]
```

By ID:

```
return $service->getBannerAttachModelHandler()->byId(1);
```

###### return

```
ItDevgroup\LaravelBannerApi\Model\BannerAttachModel
```

By banner and model:

```
return $service->getBannerAttachModelHandler()->byBannerAndModel(
    $banner,
    \App\Models\User::find(1)
);
```

###### return

```
ItDevgroup\LaravelBannerApi\Model\BannerAttachModel
```

Save:

```
return $service->getBannerAttachModelHandler()->save($attachModel);
```

###### return

```
(boolean)
```

Delete:

```
return $service->getBannerAttachModelHandler()->delete($attachModel);
```

###### return

```
(boolean)
```

Delete by banner and model:

```
return $service->getBannerAttachModelHandler()->deleteByBannerAndModel(
    $banner,
    \App\Models\User::find(1)
);
```

###### return

```
(boolean)
```

Delete by banner:

```
return $service->getBannerAttachModelHandler()->deleteByBanner(
    $banner
);
```

###### return

```
(boolean)
```

Delete by model:

```
return $service->getBannerAttachModelHandler()->deleteByModel(
    \App\Models\User::find(1)
);
```

###### return

```
(boolean)
```

Delete attach models without banner:

```
return $service->getBannerAttachModelHandler()->deleteWithoutBanner();
```

###### return

```
(boolean)
```

### Banner statistic click API

New model:

```
$bannerStatisticClick = \ItDevgroup\LaravelBannerApi\Model\BannerStatisticClickModel::register(
    $banner
);

$bannerStatisticClick = new \ItDevgroup\LaravelBannerApi\Model\BannerStatisticClickModel();
$bannerStatisticClick->page_referrer = 'https://referrer.page';

$bannerStatisticClick = $service->getBannerStatisticClickHandler()->newModel(
    $banner
);

$bannerStatisticClick = $service->getBannerStatisticClickHandler()->newModel(
    $banner,
    'https://referrer.page',
    [
        'custom1' => 'property1',
        'custom2' => 'property2',
    ]
);
```

List:

Event support - ItDevgroup\LaravelBannerApi\Event\BannerApiStatisticClickListBuilderEvent

```
return $service->getBannerStatisticClickHandler()->list();

$filter = new \ItDevgroup\LaravelBannerApi\Model\BannerStatisticClickFilter();
$option = new \ItDevgroup\LaravelBannerApi\BannerRequestOption();
return $service->getBannerStatisticClickHandler()->list($filter, $option);
```

###### return

```
Illuminate\Contracts\Pagination\LengthAwarePaginator
Illuminate\Database\Eloquent\Collection
ItDevgroup\LaravelBannerApi\Model\BannerStatisticClickModel[]
```

By ID:

```
return $service->getBannerStatisticClickHandler()->byId(1);
```

###### return

```
ItDevgroup\LaravelBannerApi\Model\BannerStatisticClickModel
```

Save:

```
$bannerStatisticClick = $service->getBannerStatisticClickHandler()->byId(1); // or new model
return $service->getBannerStatisticClickHandler()->save($bannerStatisticClick);
```

###### return

```
(boolean)
```

Delete:

```
$bannerStatisticClick = $service->getBannerStatisticClickHandler()->byId(1);
return $service->getBannerStatisticClickHandler()->delete($bannerStatisticClick);
```

###### return

```
(boolean)
```

### General methods

Delete banner and all his relations

```
$service->deleteBanner($banner);
```

Increase the counter of banner impressions by +1

```
$service->showBanner($banner);
```

Add click on banner to statistics

```
$service->clickBanner(
    $banner
);

$service->clickBanner(
    $banner,
    'https://referrer.page',
    [
        'custom1' => 'property1',
        'custom2' => 'property2',
    ]
);
```

### Filters

Each filter has its own set of filtering methods, example for banner positions:

```
$filter = (new \ItDevgroup\LaravelBannerApi\Model\BannerPositionFilter())
    ->setTitle('tle 2')
    ->setGroupName('group 1');
```

### Class ItDevgroup\LaravelBannerApi\BannerRequestOption

An optional class that handles sorting, pagination and other things in queries.

```
$option = (new \ItDevgroup\LaravelBannerApi\BannerRequestOption())
    ->setPage(1)
    ->setPerPage(20)
    ->setSortField('id')
    ->setSortDirection('asc')
    ->setIsDisableFilter(true) // disable system filter
    ->setIsDisableSort(true); // disable system sorting
```

## Events

It is recommended to write in the readme information that the event banner system is used so that another programmer knows about the implicit modifications made.

Connecting listeners to events is done by standard laravel means.

List of supported events:

- ItDevgroup\LaravelBannerApi\Event\BannerApiPositionListBuilderEvent -
  Event of building a query for a selection of banner positions
  
- ItDevgroup\LaravelBannerApi\Event\BannerApiBannerListBuilderEvent -
  Event of building a query for a selection of banners
  
- ItDevgroup\LaravelBannerApi\Event\BannerApiImageListBuilderEvent -
  Event of building a query for a selection of banner images
  
- ItDevgroup\LaravelBannerApi\Event\BannerApiStatisticClickListBuilderEvent -
  Event of building a query for a selection of banner statistic clicks

Example structure listener for event BannerApiPositionListBuilderEvent:

```
<?php

namespace App\Listeners;

use ItDevgroup\LaravelBannerApi\Event\BannerApiPositionListBuilderEvent;

/**
 * Class BannerApiPositionListBuilderListener
 * @package App\Listeners
 */
class BannerApiPositionListBuilderListener
{
    /**
     * @param BannerApiPositionListBuilderEvent $event
     */
    public function handle(BannerApiPositionListBuilderEvent $event)
    {
    }
}
```

Example usage:

```
<?php

namespace App\Listeners;

use ItDevgroup\LaravelBannerApi\Event\BannerApiPositionListBuilderEvent;
use App\CustomBannerPositionFilter;

/**
 * Class BannerApiPositionListBuilderListener
 * @package App\Listeners
 */
class BannerApiPositionListBuilderListener
{
    /**
     * @param BannerApiPositionListBuilderEvent $event
     */
    public function handle(BannerApiPositionListBuilderEvent $event)
    {
        // if used custom filter from this entity, recommended check by class
        if (!$event->getFilter() instanceof CustomBannerPositionFilter) {
            return;
        }
        
        // it is recommended to check for the presence of a filter object
        if ($event->getFilter()) {
            $event->getBuilder()->where(
                'title',
                'like',
                sprintf('%%%s%%', $event->getFilter()->getTitle())
            );
        }
        
        $event->getBuilder()->orderBy('id', 'asc');
        
        // it is recommended to check for the presence of the options object
        if ($event->getOption()) {
            $event->getOption()->setPage(1);
            $event->getOption()->setPerPage(25);
        }
    }
}
```

For other events a similar structure of listeners is used.

## Trait

ItDevgroup\LaravelBannerApi\Model\BannerRelationModelTrait

Used in external models to link your external models to the banner package.

Adds property to your model, example for User model:

```
$user->banners; // returns all banner attach models for the current model
```

## Console commands

- `php artisan banner:api:toggle` - activate/deactivate banners by time (for cron)
- `php artisan banner:api:optimization` - optimization of tables, delete entries without banner (for cron)

