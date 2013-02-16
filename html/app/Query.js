var Query = Backbone.Model.extend({
    defaults: {
        query: "",
        name: "",
        updatedAt: "",
        isFavorite: false,
        isCurrent: false,
        hasErrors: false,
        lastError: "",
        profiles: []
    },

    select: function() {
        this.collection.resetCurrent();
        this.set('isCurrent', true);
        this.save();

        this.trigger('select', this);
    },

    title: function() {
        return this.name();
    },

    name: function() {
        return this.get('name');
    },

    appositeQueries: function() {
        var self = this;
        return this.collection.filter(function(query) {
            return self.id != query.id && !query.get('hasErrors') && query.get('profiles').status && query.get('isFavorite');
        })
    }
});

var Queries = Backbone.Collection.extend({
    localStorage: new Backbone.LocalStorage("queries"),
    model: Query,

    recent: function() {
        var queries = this.filter(function(query) {
            return !query.get('isFavorite');
        });
        queries = _.last(queries, 10);
        return queries.reverse();
    },

    favorite: function() {
        var queries = this.filter(function(query) {
            return query.get('isFavorite');
        });
        return queries.reverse();
    },

    current: function() {
        var current = this.find(function(query) {
            return query.get('isCurrent');
        });

        if (!current) {
            current = this.createNew();
        }
        return current;
    },

    createNew: function() {
        var query = new Query();
        query.collection = this;
        query.set('name', 'query #' + (this.length + 1));
        return query;
    },

    resetCurrent: function() {
        this.each(function(query) {
            if (query.get('isCurrent')) {
                query.set('isCurrent', false);
                query.save();
            }
        });
    }
});

var QueriesView = Backbone.View.extend({

    events: {
        "click .profile": "profile"
    },

    templates: {
        list: null
    },

    active: null,

    emptyMessage: 'no queries',

    initialize: function(options) {

        this.templates.list = _.template($('#queries_list').html());

        this.listenTo(this.collection, "change", this.render);
        this.listenTo(this.collection, "remove", this.render);
        this.listenTo(this.collection, "reset", this.render);
    },

    profile: function(e) {
        e.preventDefault();

        var id = $(e.target).parents('li').data('id');

        this.collection.get(id).select();
    },

    queries: function() {
        return [];
    },

    render: function() {
        var html = this.templates.list({
            queries: this.queries(),
            emptyMessage: '<p>' + this.emptyMessage + '</p>'
        });
        this.$('.inner').html(html);
    }
});

var FavoriteQueriesView = QueriesView.extend({
    emptyMessage: 'Press star near query name to stick query here.',

    queries: function() {
        return this.collection.favorite();
    }
});

var RecentQueriesView = QueriesView.extend({
    emptyMessage: 'Clear setup? :) Type query and run first profile, you will see here recent queries then.',

    events: {
        "click .profile": "profile",
        "click .add": "add"
    },

    queries: function() {
        return this.collection.recent();
    },

    add: function(e) {
        e.preventDefault();

        var query = this.collection.createNew();
        this.collection.add(query);
        query.select();

    }
});