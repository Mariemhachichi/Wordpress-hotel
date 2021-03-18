<?php

namespace MPHB\CSV\Bookings;

use MPHB\PostTypes\PaymentCPT\Statuses as PaymentStatuses;

/**
 * @since 3.5.0
 */
class BookingsParser
{
    /**
     * @param \MPHB\Entities\Booking $booking
     * @param \MPHB\Entities\ReservedRoom $room
     * @param array $columns Column names.
     * @return array [Column name => Column value]
     *
     * @since 3.7.0 removed the filter "mphb_export_bookings_parse_column".
     * @since 3.7.0 added new filter "mphb_export_bookings_parse_columns".
     */
    public function parseColumns($booking, $room, $columns)
    {
        // Generage empty values for each column first
        $values = array_fill(0, count($columns), '');
        $values = array_combine($columns, $values);

        // Parse values
        foreach ($columns as $column) {            // "room-type-id"
            $parts = explode('-', $column);        // ["room", "type", "id"]
            $parts = array_map('ucfirst', $parts); // ["Room", "Type", "Id"]

            $method = 'parse' . implode('', $parts); // "parseRoomTypeId"

            if (method_exists($this, $method)) {
                $values[$column] = $this->$method($booking, $room);
            } else {
                $values[$column] = '';
            }
        }

        return apply_filters('mphb_export_bookings_parse_columns', $values, $booking, $room);
    }

    /**
     * @param \MPHB\Entities\Booking $booking
     * @param \MPHB\Entities\ReservedRoom $room
     * @return int
     */
    protected function parseBookingId($booking, $room)
    {
        return $booking->getId();
    }

    /**
     * @param \MPHB\Entities\Booking $booking
     * @param \MPHB\Entities\ReservedRoom $room
     * @return string
     */
    protected function parseBookingStatus($booking, $room)
    {
        $status = $booking->getStatus();
        return mphb_get_status_label($status);
    }

    /**
     * @param \MPHB\Entities\Booking $booking
     * @param \MPHB\Entities\ReservedRoom $room
     * @return string Check-in date in current format.
     */
    protected function parseCheckIn($booking, $room)
    {
        return $booking->getCheckInDate()->format(MPHB()->settings()->dateTime()->getDateFormat());
    }

    /**
     * @param \MPHB\Entities\Booking $booking
     * @param \MPHB\Entities\ReservedRoom $room
     * @return string Check-out date in current format.
     */
    protected function parseCheckOut($booking, $room)
    {
        return $booking->getCheckOutDate()->format(MPHB()->settings()->dateTime()->getDateFormat());
    }

    /**
     * @param \MPHB\Entities\Booking $booking
     * @param \MPHB\Entities\ReservedRoom $room
     * @return string Room type name.
     */
    protected function parseRoomType($booking, $room)
    {
        $roomTypeId = $this->parseRoomTypeId($booking, $room);
        $roomType = MPHB()->getRoomTypeRepository()->findById($roomTypeId);

        return !is_null($roomType) ? $roomType->getTitle() : '';
    }

    /**
     * @param \MPHB\Entities\Booking $booking
     * @param \MPHB\Entities\ReservedRoom $room
     * @return int Room type ID.
     */
    protected function parseRoomTypeId($booking, $room)
    {
        $roomTypeId = $room->getRoomTypeId();
        $roomTypeId = MPHB()->translation()->getOriginalId($roomTypeId, MPHB()->postTypes()->roomType()->getPostType());

        return $roomTypeId;
    }

    /**
     * @param \MPHB\Entities\Booking $booking
     * @param \MPHB\Entities\ReservedRoom $room
     * @return string Reserved room name (physical accommodation).
     */
    protected function parseRoom($booking, $room)
    {
        $roomId = $room->getRoomId();
        $roomId = MPHB()->translation()->getOriginalId($roomId, MPHB()->postTypes()->room()->getPostType());

        $accommodation = MPHB()->getRoomRepository()->findById($roomId);

        return !is_null($accommodation) ? $accommodation->getTitle() : '';
    }

    /**
     * @param \MPHB\Entities\Booking $booking
     * @param \MPHB\Entities\ReservedRoom $room
     * @return string Rate name.
     */
    protected function parseRate($booking, $room)
    {
        $rateId = $room->getRateId();
        $rateId = MPHB()->translation()->getOriginalId($rateId, MPHB()->postTypes()->rate()->getPostType());

        $rate = MPHB()->getRateRepository()->findById($rateId);

        return !is_null($rate) ? $rate->getTitle() : '';
    }

    /**
     * @param \MPHB\Entities\Booking $booking
     * @param \MPHB\Entities\ReservedRoom $room
     * @return int
     */
    protected function parseAdults($booking, $room)
    {
        return $room->getAdults();
    }

    /**
     * @param \MPHB\Entities\Booking $booking
     * @param \MPHB\Entities\ReservedRoom $room
     * @return int
     */
    protected function parseChildren($booking, $room)
    {
        return $room->getChildren();
    }

    /**
     * @param \MPHB\Entities\Booking $booking
     * @param \MPHB\Entities\ReservedRoom $room
     * @return string
     */
    protected function parseServices($booking, $room)
    {
        $reservedServices = $room->getReservedServices();

        if (empty($reservedServices)) {
            return '';
        }

        $services = array();

        foreach ($reservedServices as $reservedService) {
            $reservedService = MPHB()->translation()->translateReservedService($reservedService);

            $service = $reservedService->getTitle();

            if ($reservedService->isPayPerAdult()) {
                $service .= ' ' . sprintf(_n('x %d guest', 'x %d guests', $reservedService->getAdults(), 'motopress-hotel-booking'), $reservedService->getAdults());
            }

            if ($reservedService->isFlexiblePay()) {
                $service .= ' ' . sprintf(_n('x %d time', 'x %d times', $reservedService->getQuantity(), 'motopress-hotel-booking'), $reservedService->getQuantity());
            }

            $services[] = $service;
        }

        return implode(', ', $services);
    }

    /**
     * @param \MPHB\Entities\Booking $booking
     * @param \MPHB\Entities\ReservedRoom $room
     * @return string
     */
    protected function parseFirstName($booking, $room)
    {
        return $booking->getCustomer()->getFirstName();
    }

    /**
     * @param \MPHB\Entities\Booking $booking
     * @param \MPHB\Entities\ReservedRoom $room
     * @return string
     */
    protected function parseLastName($booking, $room)
    {
        return $booking->getCustomer()->getLastName();
    }

    /**
     * @param \MPHB\Entities\Booking $booking
     * @param \MPHB\Entities\ReservedRoom $room
     * @return string
     */
    protected function parseEmail($booking, $room)
    {
        return $booking->getCustomer()->getEmail();
    }

    /**
     * @param \MPHB\Entities\Booking $booking
     * @param \MPHB\Entities\ReservedRoom $room
     * @return string
     */
    protected function parsePhone($booking, $room)
    {
        return $booking->getCustomer()->getPhone();
    }

    /**
     * @param \MPHB\Entities\Booking $booking
     * @param \MPHB\Entities\ReservedRoom $room
     * @return string
     */
    protected function parseCountry($booking, $room)
    {
        return $booking->getCustomer()->getCountry();
    }

    /**
     * @param \MPHB\Entities\Booking $booking
     * @param \MPHB\Entities\ReservedRoom $room
     * @return string
     */
    protected function parseAddress($booking, $room)
    {
        return $booking->getCustomer()->getAddress1();
    }

    /**
     * @param \MPHB\Entities\Booking $booking
     * @param \MPHB\Entities\ReservedRoom $room
     * @return string
     */
    protected function parseCity($booking, $room)
    {
        return $booking->getCustomer()->getCity();
    }

    /**
     * @param \MPHB\Entities\Booking $booking
     * @param \MPHB\Entities\ReservedRoom $room
     * @return string
     */
    protected function parseState($booking, $room)
    {
        return $booking->getCustomer()->getState();
    }

    /**
     * @param \MPHB\Entities\Booking $booking
     * @param \MPHB\Entities\ReservedRoom $room
     * @return string
     */
    protected function parsePostcode($booking, $room)
    {
        return $booking->getCustomer()->getZip();
    }

    /**
     * @param \MPHB\Entities\Booking $booking
     * @param \MPHB\Entities\ReservedRoom $room
     * @return string
     */
    protected function parseCustomerNote($booking, $room)
    {
        return $booking->getNote();
    }

    /**
     * @param \MPHB\Entities\Booking $booking
     * @param \MPHB\Entities\ReservedRoom $room
     * @return string
     */
    protected function parseGuestName($booking, $room)
    {
        return $room->getGuestName();
    }

    /**
     * @param \MPHB\Entities\Booking $booking
     * @param \MPHB\Entities\ReservedRoom $room
     * @return string
     */
    protected function parseCoupon($booking, $room)
    {
        return $booking->getCouponCode();
    }

    /**
     * @param \MPHB\Entities\Booking $booking
     * @param \MPHB\Entities\ReservedRoom $room
     * @return string
     */
    protected function parsePrice($booking, $room)
    {
        $price = mphb_format_price($booking->getTotalPrice(), array('as_html' => false));
        return html_entity_decode($price); // Decode #&36; into $
    }

    /**
     * @param \MPHB\Entities\Booking $booking
     * @param \MPHB\Entities\ReservedRoom $room
     * @return string
     */
    protected function parsePaid($booking, $room)
    {
        $payments = MPHB()->getPaymentRepository()->findAll(array('booking_id' => $booking->getId()));
        $paid = 0.0;

        foreach ($payments as $payment) {
            if ($payment->getStatus() == PaymentStatuses::STATUS_COMPLETED) {
                $paid += $payment->getAmount();
            }
        }

        return html_entity_decode(mphb_format_price($paid, array('as_html' => false)));
    }

    /**
     * @param \MPHB\Entities\Booking $booking
     * @param \MPHB\Entities\ReservedRoom $room
     * @return string
     */
    protected function parsePayments($booking, $room)
    {
        $payments = MPHB()->getPaymentRepository()->findAll(array('booking_id' => $booking->getId()));
        $paymentStrings = array();

        foreach ($payments as $payment) {
            $id      = $payment->getId();
            $status  = mphb_get_status_label($payment->getStatus());
            $amount  = html_entity_decode(mphb_format_price($payment->getAmount(), array('as_html' => false)));
            $gateway = MPHB()->gatewayManager()->getGateway($payment->getGatewayId());
            $method  = !is_null($gateway) ? $gateway->getAdminTitle() : $payment->getGatewayId();

            $paymentStrings[] = "#{$id},{$status},{$amount},{$method}";
        }

        return implode(';', $paymentStrings);
    }

    /**
     * @param \MPHB\Entities\Booking $booking
     * @param \MPHB\Entities\ReservedRoom $room
     * @return string
     */
    protected function parseDate($booking, $room)
    {
        return get_the_date(MPHB()->settings()->dateTime()->getDateFormat() . ' H:i:s', $booking->getId());
    }
}
