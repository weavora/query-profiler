var QueryProfileView = Backbone.View.extend({

    events: {
        "click .query .profile": "profile",
        "click .query .favorite": "favorite",
        "click .query .compare": "compare"
    },

    templates: {
        details: null,
        profiles: null,
        compare: null
    },

    connections: null,

    initialize: function(options) {
        this.templates.details = _.template($('#query_details').html());
        this.templates.profiles = _.template($('#query_profiles').html());
        this.templates.compare = _.template($('#query_compare').html());

        this.listenTo(this.collection, "select", this.render);
        this.listenTo(this.collection, "change", this.render);
        this.listenTo(this.collection, "reset", this.render);

        this.connections = options.connections;

//        this.render();
    },

    favorite: function(e) {
        e.preventDefault();
        var query = this.collection.current();

        if (query.get('isFavorite')) {
            query.set('isFavorite', false);
            query.save();
        } else {
            $('.query h1').html('<input type="text" name="name" autofocus="on" value="' + query.name() + '" />');
        }
    },

    profile: function(e) {
        e.preventDefault();

        var sql = this.$('[name="query"]').val();
        var query = this.collection.current();
        var name = (this.$('[name="name"]').length ? this.$('[name="name"]').val() : null);

        if (!query.id) {
            this.collection.add(query);
            query.select();
        }

        if (name) {
            query.set('isFavorite', true);
            query.set('name', name);
        }
        query.set('query', sql);
        query.select();


        var connection = this.connections.active();
        $.get('api.php', {
            host: connection.get('host'),
            database: connection.get('database'),
            user: connection.get('user'),
            password: connection.get('password'),
            query: query.get('query')
        }, $.proxy(this, 'saveProfile'), 'json');
    },

    saveProfile: function(profile) {
        var query = this.collection.current();
        if (profile.error_message) {
            query.set('hasErrors', true);
            query.set('lastError', profile.error_message);
        } else {
            query.set('profiles', profile.profiles);
            query.set('hasErrors', false);
            query.set('lastError', '');
        }

        query.save();
    },

    compare: function(e) {
        e.preventDefault();

        var query = this.collection.current();
        var queryStatus = query.get('profiles').status;

        var appositeQueryId = this.$('.compare-with select').val();
        var appositeQuery = this.collection.get(appositeQueryId);
        var appositeQueryStatus = appositeQuery.get('profiles').status;

        var compare = this.templates.compare({
            query: query,
            queryStatus: queryStatus,

            appositeQuery: appositeQuery,
            appositeQueryStatus: appositeQueryStatus,

            statuses: _.union(_.keys(queryStatus), _.keys(appositeQueryStatus))
        });
        this.$('.profiles').html(compare);
    },

    render: function() {
        var query = this.collection.current();
        var queryDetails = this.templates.details({
            query: this.collection.current(),
            queries: this.collection
        });
        this.$('.query').html(queryDetails);

        var queryProfiles = '';
        if (query.get('profiles')) {
            queryProfiles = this.templates.profiles({
                profiles: query.get('profiles')
            });
        }
        this.$('.profiles').html(queryProfiles);
    }
});
