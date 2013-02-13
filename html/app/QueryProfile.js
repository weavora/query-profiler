var QueryProfileView = Backbone.View.extend({

    events: {
        "click .query .profile": "profile"
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

    profile: function(e) {
        e.preventDefault();

        var query = this.collection.current();
        query.set('query', this.$('[name="query"]').val());
        query.select();

        if (!query.id) {
            this.collection.add(query);
        } else {
            query.save();
        }

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
