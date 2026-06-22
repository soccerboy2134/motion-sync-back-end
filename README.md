# MotionSync

## Requirements and setup
- Install Laravel. `https://laravel.com/docs/13.x/installation`
- Run `composer install` and `npm install` to install the required packages and build the CSS.
- Copy .env.example to .env, set SQL credentials. 
- Run `php artisan key:generate` to generate laravel's key (required for encryption; will fail without)
- Run `php artisan migrate` to migrate the tables.
- Optional, but recommended: Run `php artisan db:seed` to seed the database with test users. Their password will be "GGG".

## API Endpoints
All endpoints start with /api/ 

### User
* `POST /user/store`

  * Throttle: **10 requests per minute**
  * 7 arguments

    * `name`

      * Required.
      * User's name.
    * `display_name`

      * Required.
      * Must be unique.
      * User's public display name.
    * `gender`

      * Required.
      * User's gender.
    * `date_of_birth`

      * Required.
      * Must be a valid date.
    * `visibility`

      * Boolean.
      * Optional.
      * Controls profile visibility.
    * `password`

      * Required.
      * Minimum length: 1 character.
    * `password_confirmation`

      * Required.
      * Must match `password`.
  * Returns the newly created `user`.

* `POST /user/authenticate`

  * Throttle: **10 requests per minute**
  * 2 arguments

    * `display_name`

      * Required.
      * User's display name.
    * `password`

      * Required.
      * String.
      * Minimum length: 1 character.
  * Returns an authentication token and the authenticated `user`.

* `GET /user`

  * Authentication required.
  * Throttle: **100 requests per minute**
  * No arguments.
  * Returns the authenticated `user`.
  * Includes:

    * `workouts_sum_points`

      * Total points accumulated across all workouts.
    * `achievements`

      * Completed achievements for the authenticated user.

* `GET /user/search/{query}`

  * Authentication required.
  * Throttle: **100 requests per minute**
  * 1 argument

    * `query`

      * Required.
      * Search term used to match `display_name`.
  * Returns a collection of matching `users`.

* `GET /user/{id}`

  * Authentication required.
  * Throttle: **100 requests per minute**
  * 1 argument

    * `id`

      * Required.
      * User ID.
  * Returns the specified `user`.
  * Includes:

    * `workouts_sum_points`

      * Total points accumulated across all workouts.
    * `achievements`

      * Achievements and progress for the user.
  * Private profiles can only be viewed by their owner.
  * Returns `403 Forbidden` if the profile is private.

* `PATCH /user/{id}`

  * Authentication required.
  * Throttle: **100 requests per minute**
  * 1 path argument

    * `id`

      * Required.
      * User ID to update.
  * Accepts any valid user update fields.
  * Returns the updated `user`.


### Workout
* `GET /workout`

  * Authentication required.
  * Throttle: **100 requests per minute**
  * No arguments.
  * Returns all workouts belonging to the authenticated `user`.

* `GET /workout/{id}`

  * Authentication required.
  * Throttle: **100 requests per minute**
  * 1 argument

    * `id`

      * Required.
      * User ID.
  * Returns all workouts belonging to the specified `user`.
  * Only available if:

    * The user's profile is public.
    * Or the authenticated user is requesting their own workouts.
  * Returns `403 Forbidden` if the user's workouts are private.

* `POST /workout/store`

  * Authentication required.
  * Throttle: **100 requests per minute**
  * 1 argument

    * `waypoints`

      * Required.
      * Array of GPS waypoints.
      * Used to calculate workout distance, speed, type, and points.
      * Each waypoint must contain:

        * `lat`

          * Required.
          * Numeric.
          * Must be between `-90` and `90`.
        * `lon`

          * Required.
          * Numeric.
          * Must be between `-180` and `180`.
        * `timestamp`

          * Required.
          * Timestamp of the recorded location.
  * Returns the created `workout`.
  * Automatically calculates:

    * `length`
    * `speed`
    * `type`
    * `points`
  * Automatically updates achievement progress.

* `DELETE /workout/{id}`

  * Authentication required.
  * Throttle: **100 requests per minute**
  * 1 argument

    * `id`

      * Required.
      * Workout ID.
  * Deletes the specified `workout`.
  * The authenticated user must own the workout.
  * Returns `403 Forbidden` if the workout belongs to another user.


### Friend
* `GET /friend`

  * Authentication required.
  * Throttle: **100 requests per minute**
  * No arguments.
  * Returns all friendship relationships associated with the authenticated `user`.
  * Results are grouped by relationship status.
  * Each relationship includes a `friend` object representing the other user.

* `GET /friend/find/{id}`

  * Authentication required.
  * Throttle: **100 requests per minute**
  * 1 argument

    * `id`

      * Required.
      * User ID.
  * Returns any existing relationship records between the authenticated user and the specified `user`.

* `POST /friend/{id}`

  * Authentication required.
  * Throttle: **100 requests per minute**
  * 1 argument

    * `id`

      * Required.
      * User ID of the user to send a friend request to.
  * Creates a new friend request.
  * The target user must exist.
  * Users cannot send friend requests to themselves.
  * Users cannot create duplicate relationships.
  * Returns the created friendship record.
  * Possible errors:

    * `404` if the target user does not exist.
    * `400` if a relationship already exists.

* `PATCH /friend`

  * Authentication required.

  * Throttle: **100 requests per minute**

  * 2 arguments

    * `user_id`

      * Required.
      * User ID of the target user.
    * `status`

      * Required.
      * Accepted values:

        * `accepted`
        * `denied`
        * `unfriend`
        * `block`
        * `unblock`

  * Updates the relationship between the authenticated user and the specified user.

  * Status actions:

    * `accepted`

      * Accepts a pending friend request.
      * Only the receiver may perform this action.
    * `denied`

      * Denies a pending friend request.
    * `unfriend`

      * Removes an existing friendship.
    * `block`

      * Blocks the specified user.
      * Removes any existing relationship before creating the block.
    * `unblock`

      * Removes a previously created block.
      * Only the user who created the block may unblock.

  * Returns the updated relationship record or a confirmation message.


### Leaderboard
* `GET /leaderboard`

  * Authentication required.
  * Throttle: **100 requests per minute**
  * No arguments.
  * Returns the latest global leaderboard.
  * Each entry includes:

    * Leaderboard information.
    * Associated `user` data.
  * Rankings are based on total workout points.

* `GET /leaderboard/friends`

  * Authentication required.
  * Throttle: **100 requests per minute**
  * No arguments.
  * Returns a leaderboard containing only the authenticated user's friends.
  * Rankings are based on total workout points.
  * Each entry includes:

    * Leaderboard information.
    * Associated `user` data.
  * Returns `400 Bad Request` if the user has no friends.

* `POST /leaderboard`

  * Authentication required.
  * Admin permissions required.
  * Throttle: **100 requests per minute**
  * No arguments.
  * Generates a new global leaderboard snapshot.
  * Selects the top 10 users with the highest total workout points.
  * Creates new leaderboard entries for those users.
  * Progresses applicable achievement chains.
  * Returns the newly generated leaderboard entries.

* `DELETE /leaderboard/{id}`

  * Authentication required.
  * Admin permissions required.
  * Throttle: **100 requests per minute**
  * 1 argument

    * `id`

      * Required.
      * Leaderboard entry ID.
  * Deletes the specified leaderboard entry.
  * Returns a confirmation message upon success.


### Skins
* `GET /skins`

  * Authentication required.
  * Throttle: **100 requests per minute**
  * No arguments.
  * Returns all available skins.
  * Each skin includes:

    * Skin information.
    * `is_unlocked`

      * Boolean indicating whether the authenticated user has unlocked the skin.

* `GET /skins/unlocked`

  * Authentication required.
  * Throttle: **100 requests per minute**
  * No arguments.
  * Returns all skins unlocked by the authenticated user.

* `GET /skins/{id}`

  * Authentication required.
  * Throttle: **200 requests per minute**
  * 1 argument

    * `id`

      * Required.
      * Skin ID.
  * Returns the skin image.
  * Redirects to the stored image file associated with the skin.


### Theme 
* `GET /theme`

  * Authentication required.
  * Throttle: **200 requests per minute**
  * No arguments.
  * Returns all available themes.

* `POST /theme/store`

  * Authentication required.
  * Admin permissions required.
  * Throttle: **100 requests per minute**
  * 9 arguments

    * `name`

      * Required.
      * Theme name.
    * `bg`

      * Required.
      * Background color.
    * `surface`

      * Required.
      * Surface color.
    * `primary`

      * Required.
      * Primary color.
    * `onPrimary`

      * Required.
      * Text/icon color displayed on primary elements.
    * `accent`

      * Required.
      * Accent color.
    * `text`

      * Required.
      * Primary text color.
    * `muted`

      * Required.
      * Muted text color.
    * `border`

      * Required.
      * Border color.
  * Creates a new theme.
  * Returns the created `theme`.

* `DELETE /theme/{id}`

  * Authentication required.
  * Admin permissions required.
  * Throttle: **100 requests per minute**
  * 1 argument

    * `id`

      * Required.
      * Theme ID.
  * Deletes the specified `theme`.
  * Returns a confirmation message upon success.
