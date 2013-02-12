var Query = Backbone.Model.extend({
    defaults: {
        query: "",
        updatedAt: "",
        isFavorite: false,
        profiles: []
    }
});

var Queries = Backbone.Collection.extend({
    localStorage: new Backbone.LocalStorage("queries"),
    model: Query
});