<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CustomerModel;
use App\Models\Distributer;
use App\Models\DoctorModel;
use App\Models\BankAccount;
use App\Models\BatchModel;
use App\Models\CashManagement;
use App\Models\DistributorPrchesReturnTable;
use App\Models\ExpenseModel;
use App\Models\FinalPurchesItem;
use App\Models\iteamPurches;
use App\Models\IteamsModel;
use App\Models\LedgerModel;
use App\Models\ItemLocation;
use App\Models\LogsModel;
use App\Models\PaymentDetails;
use App\Models\PaymentModel;
use App\Models\PurchesDetails;
use App\Models\PurchesModel;
use App\Models\PurchesPayment;
use App\Models\PurchesPaymentDetails;
use App\Models\PurchesReturn;
use App\Models\PurchesReturnDetails;
use App\Models\PurchesReturnHistory;
use App\Models\salesDetails;
use App\Models\SalesFinalIteam;
use App\Models\SalesIteam;
use App\Models\SalesModel;
use App\Models\SalesReturn;
use App\Models\SalesReturnDetails;
use App\Models\SalesReturnEdit;
use App\Models\FrontRole;
use App\Models\frontRolePermissions;
use App\Models\OnlineOrder;
use App\Models\adjustStock;
use App\Models\BatchStock;

class deleteTabel extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        CustomerModel::truncate();
        Distributer::truncate();
        DoctorModel::truncate();
        Distributer::truncate();
        BankAccount::truncate();
        BatchModel::truncate();
        CashManagement::truncate();
        DistributorPrchesReturnTable::truncate();
        ExpenseModel::truncate();
        FinalPurchesItem::truncate();
        iteamPurches::truncate();
        IteamsModel::truncate();
        ItemLocation::truncate();
        LedgerModel::truncate();
        LogsModel::truncate();
        PaymentDetails::truncate();
        PaymentModel::truncate();
        PurchesDetails::truncate();
        PurchesModel::truncate();
        PurchesPayment::truncate();
        PurchesPaymentDetails::truncate();
        PurchesReturn::truncate();
        PurchesReturnDetails::truncate();
        PurchesReturnHistory::truncate();
        salesDetails::truncate();
        SalesFinalIteam::truncate();
        SalesIteam::truncate();
        SalesModel::truncate();
        SalesReturn::truncate();
        SalesReturnDetails::truncate();
        SalesReturnEdit::truncate();
        FrontRole::truncate();
        frontRolePermissions::truncate();
        OnlineOrder::truncate();
        adjustStock::truncate();
    }
}
