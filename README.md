# Installation

In addition to the normal steps one would take for a standard Laravel project:

```
# update .env
ACME_API_KEY="CLAIR-ABC-123"
ACME_STRATEGY="fake"
QUEUE_CONNECTION=database

# create `.env.testing` for separate testing DB

# run migrations
php artisan migrate
php artisan migrate --env=testing

# seed local DB
php artisan db:seed
```

# Tests

```
php artisan test
php artisan test --filter :keyword # to test individual class or method
```

#### Sync PayItems for Business

```
# queue job by business external_id
php artisan pay-items:sync-for-busines :business_external_id
php artisan pay-items:sync-for-busines "a-valid-fake-business-external-id" # valid example
php artisan pay-items:sync-for-busines "some-invalid-external-id" # reserved invalid example

# run queued jobs
php artisan queue:work --stop-when-empty

# retry failed jobs
php artisan queue:retry all

# to manually replicate a job failure due to invalid api key
# in `.env`, update `ACME_API_KEY` to invalid value
php artisan pay-items:sync-for-busines "a-valid-fake-business-external-id"
php artisan queue:work --stop-when-empty
```

# Notes / Closing Thoughts
- I skipped `x-api-key` into a header object, as I don't make any true curl requests, though I think I sufficiently managed to satisfy the other requirements surrounding a request with invalid authentication credentials. Likewise, I don't actually fully construct the theoretical endpoint for the same reasons.
- My PHP/Laravel is a little rusty. I haven't actively developed in either since early 2019, so I very well might be overlooking some newer best practices.
- My main service class uses a static method for its main method, but this was a bit weird to do in practice. In my most recent (ruby) experience, using static/function methods was preferred where possible, but I'm re-learning here that Laravel doesn't really share that bias. With more time, I think I might switch out to instance methods, but take advantage of Laravel Facades to make mocking easier.
- Re: currency. I used "cents", so I rounded to full cents accordingly.
- In the past, I used phpDoc to create better comments around my methods and properties, but I couldn't get my IDE to implement properly within my "time-box".