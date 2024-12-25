// Define ticket array with initial tickets
const tickets = [
    {
        id: 1,
        subject: "Issue with Payment",
        status: "open",
        messages: [
            { sender: "Customer", text: "I am unable to complete my payment." },
            { sender: "admin", text: "Please try using a different payment method." }
        ]
    },
    {
        id: 2,
        subject: "Ticket Delivery Problem",
        status: "pending",
        messages: [
            { sender: "Customer", text: "I haven't received my tickets yet." },
            { sender: "admin", text: "We are checking the delivery status." }
        ]
    },
    {
        id: 3,
        subject: "Issue with Account",
        status: "resolved",
        messages: [
            { sender: "Customer", text: "I can't log into my account." },
            { sender: "admin", text: "Your password has been reset." }
        ]
    }
];

// Variable to store current ticket being viewed in the modal
let currentTicket = null;
let selectedStatus = null; // Store selected status for confirmation

// Function to handle sidebar navigation
function handleNavigation() {
    const sidebarLinks = document.querySelectorAll('.sidebar ul li a');
    const pages = document.querySelectorAll('.page');

    sidebarLinks.forEach(link => {
        link.addEventListener('click', function (event) {
            event.preventDefault();
            const targetPage = this.getAttribute('data-page');

            // Remove active class from all links and add to the clicked one
            sidebarLinks.forEach(link => link.classList.remove('active'));
            this.classList.add('active');

            // Hide all pages
            pages.forEach(page => page.classList.add('hidden'));

            // Show the selected page
            document.getElementById(`${targetPage}-page`).classList.remove('hidden');
        });
    });
}

// Function to render the ticket list
function renderTickets(filteredTickets = tickets) {
    const ticketList = document.getElementById("ticketList");
    ticketList.innerHTML = ""; // Clear the list before rendering new tickets

    filteredTickets.forEach(ticket => {
        const ticketItem = document.createElement("div");
        ticketItem.className = "ticket-item";
        ticketItem.innerHTML = `<h4>${ticket.subject}</h4><p>Status: ${ticket.status}</p>`;
        ticketItem.onclick = () => openTicketModal(ticket);
        ticketList.appendChild(ticketItem);
    });
}

// Function to filter tickets by status
function filterTicketsByStatus() {
    const statusFilter = document.getElementById("ticket-status-filter").value;

    // If 'all' is selected, display all tickets, otherwise filter based on the status
    const filteredTickets = statusFilter === 'all' ? tickets : tickets.filter(ticket => ticket.status === statusFilter);

    // Re-render the ticket list with the filtered tickets
    renderTickets(filteredTickets);
}

// Function to open the ticket modal
function openTicketModal(ticket) {
    currentTicket = ticket; // Set current ticket to the one being viewed
    selectedStatus = null; // Reset the selected status
    document.getElementById("ticketModal").classList.add("active");

    const ticketInfo = document.getElementById("ticket-info");
    ticketInfo.innerHTML = `<h4>Subject: ${ticket.subject}</h4><p>Status: ${ticket.status}</p>`;

    const ticketStatus = document.getElementById("ticketStatus");
    ticketStatus.value = ticket.status;

    const chatBox = document.getElementById("chatBox");
    chatBox.innerHTML = "";

    ticket.messages.forEach(message => {
        const messageItem = document.createElement("div");
        messageItem.className = `message ${message.sender === 'admin' ? 'admin' : 'customer'}`;
        messageItem.innerHTML = `<p><strong>${message.sender}:</strong> ${message.text}</p>`;
        chatBox.appendChild(messageItem);
    });

    document.getElementById("adminMessage").value = "";

    // Disable confirm button initially
    document.getElementById("confirmButton").disabled = true;
}

// Function to close the ticket modal
function closeModal() {
    document.getElementById("ticketModal").classList.remove("active");
    currentTicket = null; // Reset current ticket
}

// Function to send a message
function sendMessage() {
    const adminMessage = document.getElementById("adminMessage").value;
    if (adminMessage.trim() === "") return;

    currentTicket.messages.push({ sender: "admin", text: adminMessage });
    renderMessages(); // Re-render the chat messages
    document.getElementById("adminMessage").value = ""; // Clear message input
}

// Function to render the chat messages
function renderMessages() {
    const chatBox = document.getElementById("chatBox");
    chatBox.innerHTML = "";

    currentTicket.messages.forEach(message => {
        const messageItem = document.createElement("div");
        messageItem.className = `message ${message.sender === 'admin' ? 'admin' : 'customer'}`;
        messageItem.innerHTML = `<p><strong>${message.sender}:</strong> ${message.text}</p>`;
        chatBox.appendChild(messageItem);
    });
}

// Function to handle ticket status change
function onStatusChange() {
    const status = document.getElementById("ticketStatus").value;
    selectedStatus = status;

    // Enable the confirm button when a new status is selected
    document.getElementById("confirmButton").disabled = false;
}

// Function to confirm and update the ticket status
function confirmStatusUpdate() {
    if (selectedStatus) {
        // Update the status of the current ticket
        currentTicket.status = selectedStatus;

        // Re-render the ticket info in the modal
        const ticketInfo = document.getElementById("ticket-info");
        ticketInfo.querySelector("p").textContent = `Status: ${selectedStatus}`;

        // Update the ticket list to reflect the new status
        renderTickets();

        // Disable confirm button again
        document.getElementById("confirmButton").disabled = true;
        alert(`Ticket status updated to: ${selectedStatus}`);
    }
}

// Initialize the page and set up navigation
document.addEventListener('DOMContentLoaded', () => {
    handleNavigation();  // Handle navigation between pages
    renderTickets();     // Render ticket list (all tickets by default)
});


