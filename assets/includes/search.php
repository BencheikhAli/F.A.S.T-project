<!-- bare de recherche dans les page d'admin -->
<div class="header__search-content">
    <div class="form-search">
        <select name="filter" id="filter">
            <option value="all">Filtrer par role</option>
            <option value="cariste">Cariste</option>
            <option value="manager">Manager</option>
            <option value="admin">Admin</option>
        </select>
        <div class="searchBar">
            <input autocomplete="off" type="text" id="searchBar" name="search" placeholder="recherche des utilisateurs par identifiant" required>
        </div>
            <input type="submit" id="btn-search" value="Recherche" disabled>
        <div class="autocomplete-items"></div>
    </div>
    <span class="big-space"></span>
</div>
