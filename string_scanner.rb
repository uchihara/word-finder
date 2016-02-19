require 'set'
require 'pry'
require './matrix'

class StringScanner
  def initialize matrix
    @matrix = matrix
  end

  def scan length, x=0, y=0
    strings = Set.new
    walk x, y, [], length, strings
    strings
  end

  private
  def walk x, y, string, length, strings
    raise "invalid length: #{length}" if length <= 0
    raise "invalid string: #{string}" if string.length > length
    raise "invalid pos, x: #{x}, y: #{y}" unless @matrix.exists?(x, y)
    raise "invalid mark, x: #{x}, y: #{y}" if @matrix.at(x, y).marked?

    @matrix.at(x, y).mark!
    string_ = string + [@matrix.at(x, y).char]
    if string_.length == length
      strings << string_.join
    else
      [-1, 0, 1].each do |dx|
        [-1, 0, 1].each do |dy|
          if @matrix.exists?(x+dx, y+dy) && !@matrix.at(x+dx, y+dy).marked?
            walk x+dx, y+dy, string_, length, strings
          end
        end
      end
    end
    @matrix.at(x, y).unmark!
  end
end

if __FILE__ == $0
#  matrix = Matrix.new [ %w[ a b ], %w[ c d ] ]
#  scanner = StringScanner.new matrix
#  pp matrix
#  pp scanner.scan 1
#  pp scanner.scan 2
#  pp scanner.scan 3
#  matrix = Matrix.new [ %w[ a b c ], %w[ d e f ] ]
#  scanner = StringScanner.new matrix
#  pp matrix
#  pp scanner.scan 1
#  pp scanner.scan 2
#  pp scanner.scan 3
#  pp scanner.scan 4
#  matrix = Matrix.new [ %w[ a b c ], %w[ d e f ], %w[ g h i ] ]
#  scanner = StringScanner.new matrix
#  pp matrix
#  pp scanner.scan 1
#  pp scanner.scan 2
#  pp scanner.scan 3
#  pp scanner.scan 4
#  pp scanner.scan 5
#  pp scanner.scan 6
#  pp scanner.scan 7
#  pp scanner.scan 8
#  pp scanner.scan 3, 1, 1
  matrix = Matrix.new [ %w[ c o ], %w[ o l ] ]
  scanner = StringScanner.new matrix
  pp matrix
  pp scanner.scan 4, 1, 1
end
