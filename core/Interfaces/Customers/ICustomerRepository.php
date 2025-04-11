<?php
namespace Interfaces\Customers;
use Models\Customer;
use Dtos\CustomerDto;

interface ICustomerRepository{
   function records();
   function created(CustomerDto $customer);
   function CheckExistsCustomer($customerId);
   function by($customerId);


}