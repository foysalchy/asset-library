<!-- Global Share Modal -->
<div id="shareModal" class="fixed inset-0 z-[100] hidden items-center justify-center pointer-events-none font-['Outfit']">
    <div class="bg-white rounded-lg shadow-[0_20px_50px_rgba(0,0,0,0.3)] w-full max-w-md p-6 border border-gray-200 pointer-events-auto">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-xl font-bold text-gray-800" id="shareModalTitle">Share</h3>
            <button onclick="closeShareModal()" class="text-gray-400 hover:text-gray-600"><i class="fas fa-times"></i></button>
        </div>

        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Copy Link</label>
            <div class="flex">
                <input type="text" id="shareUrl" readonly class="flex-1 border border-gray-300 px-3 py-2 text-sm bg-gray-50 outline-none">
                <button onclick="copyShareLink()" class="bg-[#0071c5] text-white px-4 py-2 text-sm font-bold hover:bg-[#005ea3]">Copy</button>
            </div>
            <p id="copyStatus" class="text-green-600 text-[10px] mt-1 hidden font-bold uppercase tracking-wider">Copied to clipboard!</p>
        </div>

        <div class="flex gap-3">
            <a id="fbShare" href="#" target="_blank" class="flex-1 flex items-center justify-center bg-[#1877F2] text-white p-3 rounded shadow-sm hover:opacity-90 text-lg"><i class="fab fa-facebook-f"></i></a>
            <a id="msgShare" href="#" target="_blank" class="flex-1 flex items-center justify-center bg-gradient-to-tr from-[#006AFF] to-[#00B2FF] text-white p-3 rounded shadow-sm hover:opacity-90 text-lg"><i class="fab fa-facebook-messenger"></i></a>
            <a id="waShare" href="#" target="_blank" class="flex-1 flex items-center justify-center bg-[#25D366] text-white p-3 rounded shadow-sm hover:opacity-90 text-lg"><i class="fab fa-whatsapp"></i></a>
            <a id="mailShare" href="#" class="flex-1 flex items-center justify-center bg-[#757575] text-white p-3 rounded shadow-sm hover:opacity-90 text-lg"><i class="fas fa-envelope"></i></a>
        </div>
    </div>
</div>

<script>
    window.openGlobalShareModal = function(url, title = "Share Assets") {
        const encodedUrl = encodeURIComponent(url);
        const encodedTitle = encodeURIComponent(title);

        document.getElementById('shareModalTitle').innerText = title;
        document.getElementById('shareUrl').value = url;

        document.getElementById('fbShare').href = `https://www.facebook.com/sharer/sharer.php?u=${encodedUrl}`;
        document.getElementById('waShare').href = `https://api.whatsapp.com/send?text=${encodedTitle}%20${encodedUrl}`;
        document.getElementById('msgShare').href = `fb-messenger://share/?link=${encodedUrl}`;
        document.getElementById('mailShare').href = `mailto:?subject=${encodedTitle}&body=${url}`;

        const modal = document.getElementById('shareModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    window.closeShareModal = function() {
        const modal = document.getElementById('shareModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    window.copyShareLink = function() {
        const copyText = document.getElementById('shareUrl');
        copyText.select();
        navigator.clipboard.writeText(copyText.value).then(() => {
            const status = document.getElementById('copyStatus');
            status.classList.remove('hidden');
            setTimeout(() => status.classList.add('hidden'), 2000);
        });
    }
</script>
