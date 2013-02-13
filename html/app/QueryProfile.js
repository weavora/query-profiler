var QueryProfileView = Backbone.View.extend({

    events: {
        "click .query .profile": "profile",
        "click .query .favorite": "favorite"
    },

    templates: {
        details: null,
        profiles: null
    },

    connections: null,

    initialize: function(options) {
        this.templates.details = _.template($('#query_details').html());
        this.templates.profiles = _.template($('#query_profiles').html());

        this.listenTo(this.collection, "select", this.render);
        this.listenTo(this.collection, "change", this.render);
        this.listenTo(this.collection, "reset", this.render);

        this.connections = options.connections;

        this.render();
    },

    favorite: function(e) {
        e.preventDefault();
        var query = this.collection.current();

        $('.query h1').html('<input type="text" name="name" value="' + query.name() + '" />');
    },

    profile: function(e) {
        e.preventDefault();

        var sql = this.$('[name="query"]').val();
        var query = this.collection.current();
        var name = (this.$('[name="name"]').length ? this.$('[name="name"]').val() : null);

        if (!query.get('isFavorite') && sql.length && query.get('profiles')) {
            query = this.collection.findBySQL(sql);
        }

        if (!query) {
            query = this.collection.createNew();
        }

        if (!query.id) {
            this.collection.add(query);
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
        if (profile.error) {
            alert(profile.error_message);
            return ;
        }
        var query = this.collection.current();
        query.set('profiles', profile.profiles);
        query.save();
    },

    render: function() {
        var query = this.collection.current();
        var queryDetails = this.templates.details({
            query: this.collection.current()
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
