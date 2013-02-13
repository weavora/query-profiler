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
    },

    findBySQL: function(sql) {
        var hash = function(sql) {
            return sql.replace(/\s/g,'').toLowerCase();
        }

        var queryHash = hash(sql);

        return this.find(function(query) {
            return hash(query.get('query')) == queryHash;
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
            emptyMessage: this.emptyMessage
        });
        this.$('.inner').html(html);
    }
});

var FavoriteQueriesView = QueriesView.extend({
    emptyMessage: 'Press Favorite to stick query here.',

    queries: function() {
        return this.collection.favorite();
    }
});

var RecentQueriesView = QueriesView.extend({
    emptyMessage: 'Clear setup? :) Type query and run first profile, you will see here recent queries then.',

    queries: function() {
        return this.collection.recent();
    }
});