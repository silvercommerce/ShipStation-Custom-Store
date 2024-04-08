<?xml version="1.0" encoding="utf-8"?>
<Orders pages="{$PaginatedOrders.TotalPages}">
  <% loop $PaginatedOrders %>
  <Order>
    <OrderID>{$ID.CDATA}</OrderID>
    <OrderNumber>{$FullRef.CDATA}</OrderNumber>
    <OrderDate>{$StartDate.Format('M/d/y H:m')}</OrderDate>
    <OrderStatus>{$Status.CDATA}</OrderStatus>
    <LastModified>{$LastEdited.Format('M/d/y HH:mm')}</LastModified>
    <OrderTotal>{$Total.RAW}</OrderTotal>
    <TaxAmount>{$TaxTotal}</TaxAmount>
    <ShippingMethod>{$PostageTitle.CDATA}</ShippingMethod>
    <ShippingAmount>{$PostageTotal.RAW}</ShippingAmount>
    <CustomerNotes>{$SpecialInstructions.CDATA}</CustomerNotes>

    <Customer>
      <CustomerCode>{$Email.CDATA}</CustomerCode>
      <BillTo>
        <Name>{$FirstName.CDATA} {$Surname.CDATA}</Name>
        <Company>{$Company.CDATA}</Company>
        <Phone>{$PhoneNumber.CDATA}></Phone>
        <Email>{$Email.CDATA}</Email>
      </BillTo>
      <ShipTo>
        <Name>{$DeliveryFirstName.CDATA} {$DeliverySurname.CDATA}</Name>
        <Company>{$DeliveryCompany.CDATA}</Company>
        <Address1>{$DeliveryAddress1.CDATA}</Address1>
        <Address2>{$DeliveryAddress2.CDATA}</Address2>
        <City>{$DeliveryCity.CDATA}</City>
        <State>{$DeliveryCounty.CDATA}</State>
        <PostalCode>{$DeliveryPostCode.CDATA}</PostalCode>
        <Country>{$DeliveryCountry.CDATA}</Country>
      </ShipTo>
    </Customer>

    <% loop $Items %>
    <Items>
      <Item>
        <SKU>{$StockID.CDATA}</SKU>
        <Name>{$Title.CDATA}</Name>
        <ImageUrl><![CDATA[{$FindStockItem.Images.First.AbsoluteURL}]]></ImageUrl>
        <Weight>{$Weight}</Weight>
        <Quantity>{$Quantity}</Quantity>
        <UnitPrice>{$UnitPrice.RAW}</UnitPrice>
        <Options>
          <% loop $Customisations %>
          <Option>
            <Name>{$Title.CDATA}</Name>
            <Value>{$Value.CDATA}</Value>
          </Option>
          <% end_loop %>
        </Options>
      </Item>
    </Items>
    <% end_loop %>
  </Order>
  <% end_loop %>
</Orders>