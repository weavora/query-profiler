var Query = Backbone.Model.extend({
    defaults: {
        query: "",
        updatedAt: "",
        isFavorite: false,
        isCurrent: false,
        profiles: []
    },

    select: function() {
        this.collection.resetCurrent();
        this.set('isCurrent', true);
        this.save();

        this.trigger('select', this);
    },

    title: function() {
        return this.cid.replace('c', 'query #');
    },

    name: function() {
        return this.cid.replace('c', 'query #');
    }
});

var Queries = Backbone.Collection.extend({
    localStorage: new Backbone.LocalStorage("queries"),
    model: Query,

    recent: function() {
        return this.last(5).reverse();
    },

    favorite: function() {
        return this.last(5).reverse();
    },

    current: function() {
        var current = this.find(function(query) {
            return query.get('isCurrent');
        });

        if (!current) {
            current = new Query();
            current.collection = this;
        }
        return current;
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

    initialize: function(options) {

        this.templates.list = _.template($('#queries_list').html());

        this.listenTo(this.collection, "change", this.render);
        this.listenTo(this.collection, "remove", this.render);
        this.listenTo(this.collection, "reset", this.render);
    },

    profile: function(e) {
        e.preventDefault();

        var connection = $(e.target).parents('li');
        var index = this.collection.length - connection.index() - 1;

        this.collection.at(index).select();
    },

    queries: function() {
        return [];
    },

    render: function() {
        var html = this.templates.list({
            queries: this.queries(),
            active: this.active
        });
        this.$('.inner').html(html);
    }
});

var FavoriteQueriesView = QueriesView.extend({
    queries: function() {
        return this.collection.favorite();
    }
});

var RecentQueriesView = QueriesView.extend({
    queries: function() {
        return this.collection.recent();
    }
});