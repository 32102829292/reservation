<?= $this->extend('user/layout') ?>

<?= $this->section('content') ?>

<style>
    .logout-btn {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.4rem 0.9rem;
        border-radius: 9999px;
        background-color: #3b82f6;
        color: white;
        font-weight: 500;
    }

    .logout-btn:hover {
        background-color: #2563eb;
    }
</style>
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
                <div class="flex items-center gap-4 mb-3 md:mb-0">
                    <h2 class="text-2xl font-semibold text-blue-900">New Reservation</h2>
                    <div class="flex items-center gap-2 bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-medium">
                        <i class="fa-solid fa-robot"></i> AI Fairness: Active
                    </div>
                </div>
                <a href="/logout" class="logout-btn">
                    <i class="fa-solid fa-right-from-bracket"></i> Logout
                </a>
            </div>

            <div class="max-w-xl bg-white rounded-2xl shadow p-6 mx-auto mb-6">
                <div class="flex items-center gap-2 bg-blue-100 text-blue-800 px-4 py-2 rounded-lg text-sm font-medium">
                    <i class="fa-solid fa-info-circle"></i>
                    You have <?= $remainingReservations ?? 3 ?> reservation(s) remaining this week.
                </div>
            </div>

            <div class="max-w-xl bg-white rounded-2xl shadow p-6 mx-auto mb-10">
                <form id="reservationForm" class="space-y-4">

                    <div>
                        <label class="block text-sm font-medium text-blue-700 mb-1">Resource</label>
                        <select name="resource_id" id="resourceSelect" class="w-full border rounded-lg p-2">
                            <option value="1">Computer</option>
                            <option value="2">Wifi</option>
                            <option value="3">Books</option>
                            <option value="4">E-Library Lobby</option>
                        </select>
                    </div>

                    <div id="pcNumberDiv" class="hidden">
                        <label class="block text-sm font-medium text-blue-700 mb-1">PC Number</label>
                        <select name="pc_number" class="w-full border rounded-lg p-2">
                            <option>PC-1</option>
                            <option>PC-2</option>
                            <option>PC-3</option>
                            <option>PC-4</option>
                            <option>PC-5</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-blue-700 mb-1">Date</label>
                        <input type="date" name="reservation_date" class="w-full border rounded-lg p-2" required>
                    </div>

                    <div class="flex gap-2">
                        <div class="flex-1">
                            <label class="block text-sm font-medium text-blue-700 mb-1">Start Time</label>
                            <input type="time" name="start_time" class="w-full border rounded-lg p-2" required>
                        </div>
                        <div class="flex-1">
                            <label class="block text-sm font-medium text-blue-700 mb-1">End Time</label>
                            <input type="time" name="end_time" class="w-full border rounded-lg p-2" required>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-blue-700 mb-1">Purpose</label>
                        <textarea name="purpose" rows="2" class="w-full border rounded-lg p-2"></textarea>
                    </div>

                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg py-2">
                        Reserve
                    </button>

                    <p id="reservationMessage" class="text-sm mt-2"></p>
                </form>
            </div>

            <!-- Review Modal -->
            <div id="reviewModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
                <div class="bg-white rounded-2xl p-6 w-full max-w-md shadow-lg">
                    <h3 class="text-xl font-semibold mb-4">Review Your Reservation</h3>
                    <div id="reviewContent" class="space-y-2 mb-4">
                        <!-- Reservation details will be populated here -->
                    </div>
                    <div class="flex justify-end space-x-2">
                        <button id="cancelReview" class="px-4 py-2 rounded-lg border hover:bg-gray-100">Cancel</button>
                        <button id="proceedReview" class="px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700">Proceed</button>
                    </div>
                </div>
            </div>

            <!-- E-Ticket Modal -->
            <div id="eTicketModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
                <div class="bg-white rounded-2xl p-6 w-full max-w-md shadow-lg">
                    <h3 class="text-xl font-semibold mb-4">Your E-Ticket</h3>
                    <div id="eTicketContent" class="text-center">
                        <!-- E-Ticket content will be populated here -->
                    </div>
                    <div class="flex justify-end space-x-2 mt-4">
                        <button id="downloadTicket" class="px-4 py-2 rounded-lg bg-green-600 text-white hover:bg-green-700">Download</button>
                        <button id="closeTicket" class="px-4 py-2 rounded-lg border hover:bg-gray-100">Close</button>
                    </div>
                </div>
            </div>


<?= $this->endSection() ?>

<script src="https://cdn.jsdelivr.net/npm/qrcode@1.5.3/build/qrcode.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const resourceSelect = document.getElementById('resourceSelect');
        const pcDiv = document.getElementById('pcNumberDiv');
        const form = document.getElementById('reservationForm');
        const reviewModal = document.getElementById('reviewModal');
        const eTicketModal = document.getElementById('eTicketModal');
        const reviewContent = document.getElementById('reviewContent');
        const eTicketContent = document.getElementById('eTicketContent');
        const cancelReview = document.getElementById('cancelReview');
        const proceedReview = document.getElementById('proceedReview');
        const downloadTicket = document.getElementById('downloadTicket');
        const closeTicket = document.getElementById('closeTicket');

        let reservationData = {};

        resourceSelect.addEventListener('change', () => {
            pcDiv.classList.toggle('hidden', resourceSelect.value !== '1');
        });

        form.addEventListener('submit', async (e) => {
            e.preventDefault();

            const formData = new FormData(form);
            reservationData = {
                resource_id: formData.get('resource_id'),
                pc_number: formData.get('pc_number'),
                reservation_date: formData.get('reservation_date'),
                start_time: formData.get('start_time'),
                end_time: formData.get('end_time'),
                purpose: formData.get('purpose')
            };

            // Populate review modal
            reviewContent.innerHTML = `
                <p><strong>Resource:</strong> ${resourceSelect.options[resourceSelect.selectedIndex].text}</p>
                <p><strong>PC Number:</strong> ${reservationData.pc_number || 'N/A'}</p>
                <p><strong>Date:</strong> ${reservationData.reservation_date}</p>
                <p><strong>Time:</strong> ${reservationData.start_time} - ${reservationData.end_time}</p>
                <p><strong>Purpose:</strong> ${reservationData.purpose}</p>
            `;

            reviewModal.classList.remove('hidden');
        });

        cancelReview.addEventListener('click', () => {
            reviewModal.classList.add('hidden');
        });

        proceedReview.addEventListener('click', async () => {
            reviewModal.classList.add('hidden');

            try {
                const response = await fetch('/reservation/reserve', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: new URLSearchParams(reservationData)
                });

                const result = await response.json();

                if (result.status === 'success') {
                    // Generate E-Ticket
                    generateETicket(result.e_ticket);
                    eTicketModal.classList.remove('hidden');
                } else {
                    alert(result.message);
                }
            } catch (error) {
                console.error('Error:', error);
                alert('An error occurred. Please try again.');
            }
        });

        function generateETicket(eTicketCode) {
            eTicketContent.innerHTML = `
                <div class="bg-blue-50 p-4 rounded-lg mb-4">
                    <h4 class="font-semibold text-blue-900 mb-2">Reservation Code: ${eTicketCode}</h4>
                    <div id="qrCode" class="flex justify-center mb-4"></div>
                    <p class="text-sm text-gray-600">Show this QR code to claim your reservation on ${reservationData.reservation_date}</p>
                </div>
            `;

            // Generate QR Code
            QRCode.toCanvas(document.getElementById('qrCode'), eTicketCode, { width: 128 }, function (error) {
                if (error) console.error(error);
            });
        }

        downloadTicket.addEventListener('click', () => {
            const canvas = document.querySelector('#qrCode canvas');
            if (canvas) {
                const link = document.createElement('a');
                link.download = 'e-ticket.png';
                link.href = canvas.toDataURL();
                link.click();
            }
        });

        closeTicket.addEventListener('click', () => {
            eTicketModal.classList.add('hidden');
            form.reset();
        });
    });
</script>
