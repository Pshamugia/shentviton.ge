<div id="cliparts" class="tabcontent">
    <div class="clipart-header">
        <input type="text" id="searchCliparts" class="chosen-select" placeholder="🔍 კლიპარტების ძიება">
        <select id="clipartCategory" class="chosen-select" data-placeholder="აირჩიეთ კატეგორია">
            <option value="all">ყველა კატეგორია</option>
            <option value="sport">სპორტი</option>
            <option value="cars">მანქანები</option>
            <option value="funny">სახალისო</option>
            <option value="love">სასიყვარულო</option>
            <option value="animation">ანიმაციური გმირები</option>
            <option value="animals">ცხოველთა სამყარო</option>
            <option value="emoji">ემოჯები</option>
            <option value="tigerskin">ვეფხისტყაოსანი</option>
            <option value="mamapapuri">მამაპაპური</option>
            <option value="qartuli">ქართული თემა</option>
        </select>
    </div>
    <div id="clipartContainer" class="row">
        {{-- Cliparts will be loaded here via AJAX --}}
    </div>

    <div class="text-center mt-3">
        <button id="loadMoreCliparts" class="btn btn-outline-primary">მეტის ნახვა</button>
    </div>
    <Br>
</div>
